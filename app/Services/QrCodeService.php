<?php

namespace App\Services;

use App\Models\QrCode;
use App\Models\QrCodeFile;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class QrCodeService
{
    /**
     * Generate QR code based on type and data
     */
    public function generate(string $type, array $data, ?array $colors = null): QrCode
    {
        // Generate QR code content based on type
        $qrContent = $this->generateQrContent($type, $data);
        
        // Create QR code record
        $qrCode = QrCode::create([
            'type' => $type,
            'name' => $data['name'] ?? 'Untitled QR Code',
            'data' => $data,
            'colors' => $colors ?? ['primary' => '#000000', 'secondary' => '#FFFFFF'],
        ]);

        // Generate and save QR code image
        $this->generateAndSaveImage($qrCode, $qrContent, $colors);

        return $qrCode;
    }

    /**
     * Generate QR code content based on type
     */
    protected function generateQrContent(string $type, array $data): string
    {
        return match($type) {
            'url' => $data['url'] ?? '',
            'email' => $this->generateEmailContent($data),
            'text' => $data['text'] ?? '',
            'pdf' => $data['pdf_url'] ?? '',
            'menu' => $data['menu_url'] ?? $data['menu_file_url'] ?? '',
            'coupon' => $data['coupon_image_url'] ?? $data['coupon_url'] ?? '',
            'event' => $this->generateEventContent($data),
            'app' => $data['website_url'] ?? '',
            'location' => $this->generateLocationContent($data),
            'wifi' => $this->generateWifiContent($data),
            'phone' => $this->generatePhoneContent($data),
            'mp3' => $data['mp3_url'] ?? '',
            default => '',
        };
    }

    /**
     * Generate email QR content (MAILTO format)
     */
    protected function generateEmailContent(array $data): string
    {
        $email = $data['email'] ?? '';
        $subject = isset($data['subject']) ? '?subject=' . urlencode($data['subject']) : '';
        $body = isset($data['message']) ? '&body=' . urlencode($data['message']) : '';
        
        return "mailto:{$email}{$subject}{$body}";
    }

    /**
     * Generate event QR content (vCard or URL format)
     */
    protected function generateEventContent(array $data): string
    {
        // For events, we'll return a JSON string that can be parsed by a landing page
        return json_encode([
            'type' => 'event',
            'event_name' => $data['event_name'] ?? '',
            'company_name' => $data['company_name'] ?? '',
            'description' => $data['description'] ?? '',
            'date' => $data['date'] ?? '',
            'time' => $data['time'] ?? '',
            'location' => $data['location'] ?? '',
            'amenities' => $data['amenities'] ?? [],
            'dress_code_color' => $data['dress_code_color'] ?? '',
            'contact' => $data['contact'] ?? '',
        ]);
    }

    /**
     * Generate location QR content (Google Maps format)
     */
    protected function generateLocationContent(array $data): string
    {
        $address = $data['address'] ?? '';
        return "https://www.google.com/maps/search/?api=1&query=" . urlencode($address);
    }

    /**
     * Generate WiFi QR content (WIFI format)
     */
    protected function generateWifiContent(array $data): string
    {
        $ssid = $data['ssid'] ?? '';
        $password = $data['password'] ?? '';
        $encryption = $data['encryption'] ?? 'WPA2';
        
        // WiFi QR code format according to standard:
        // For open networks: WIFI:S:SSID;;
        // For secured networks: WIFI:T:ENCRYPTION;S:SSID;P:PASSWORD;;
        
        // Escape special characters in SSID and password (semicolons, colons, backslashes, commas)
        $ssid = $this->escapeWifiString($ssid);
        $password = $this->escapeWifiString($password);
        
        $wifiString = 'WIFI:';
        
        // Only add encryption type if not "nopass"
        if ($encryption !== 'nopass') {
            $wifiString .= 'T:' . $encryption . ';';
        }
        
        // SSID is always required
        $wifiString .= 'S:' . $ssid . ';';
        
        // Only add password if encryption is not "nopass" and password is provided
        if ($encryption !== 'nopass' && !empty($password)) {
            $wifiString .= 'P:' . $password . ';';
        }
        
        // End with semicolon
        $wifiString .= ';';
        
        return $wifiString;
    }
    
    /**
     * Escape special characters in WiFi string values
     */
    protected function escapeWifiString(string $value): string
    {
        // Escape semicolons, colons, backslashes, and commas
        // According to WiFi QR code standard, these need to be escaped with backslash
        return str_replace(['\\', ';', ':', ','], ['\\\\', '\\;', '\\:', '\\,'], $value);
    }

    /**
     * Generate phone QR content (TEL format)
     */
    protected function generatePhoneContent(array $data): string
    {
        $phoneNumber = $data['phone_number'] ?? '';
        
        // Remove any non-digit characters except + for international format
        $phoneNumber = preg_replace('/[^\d+]/', '', $phoneNumber);
        
        // If phone number doesn't start with +, add tel: prefix
        // Standard format: tel:+1234567890 or tel:1234567890
        return 'tel:' . $phoneNumber;
    }

    /**
     * Generate and save QR code image
     */
    protected function generateAndSaveImage(QrCode $qrCode, string $content, ?array $colors = null): void
    {
        $primaryColor = $colors['primary'] ?? '#000000';
        $backgroundColor = $colors['secondary'] ?? '#FFFFFF';

        // Convert hex to RGB
        $primaryRgb = $this->hexToRgb($primaryColor);
        $backgroundRgb = $this->hexToRgb($backgroundColor);

        // Generate QR code
        $qrImage = QrCodeGenerator::format('png')
            ->size(500)
            ->margin(1)
            ->color($primaryRgb[0], $primaryRgb[1], $primaryRgb[2])
            ->backgroundColor($backgroundRgb[0], $backgroundRgb[1], $backgroundRgb[2])
            ->errorCorrection('H')
            ->generate($content);

        // Save to storage
        $filename = 'qr-codes/' . $qrCode->id . '_' . time() . '.png';
        Storage::disk('public')->put($filename, $qrImage);

        // Update QR code record
        $qrCode->update(['qr_image_path' => $filename]);
    }

    /**
     * Handle file upload
     */
    public function handleFileUpload(QrCode $qrCode, UploadedFile $file, string $fileType): QrCodeFile
    {
        $path = $file->store('qr-files', 'public');
        
        return QrCodeFile::create([
            'qr_code_id' => $qrCode->id,
            'file_type' => $fileType,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
        ]);
    }

    /**
     * Regenerate QR code image for an existing QR code
     */
    public function regenerateQrCode(QrCode $qrCode, ?array $colors = null): void
    {
        // Refresh the model to get latest data
        $qrCode->refresh();
        
        // Use provided colors or get from QR code
        $colors = $colors ?? $qrCode->colors ?? ['primary' => '#000000', 'secondary' => '#FFFFFF'];
        
        // Generate QR code content based on updated data
        $qrContent = $this->generateQrContent($qrCode->type, $qrCode->data);
        
        // Generate and save QR code image
        $this->generateAndSaveImage($qrCode, $qrContent, $colors);
    }

    /**
     * Generate QR code in SVG format
     */
    public function generateSvg(QrCode $qrCode): string
    {
        $content = $this->generateQrContent($qrCode->type, $qrCode->data);
        $colors = $qrCode->colors ?? ['primary' => '#000000', 'secondary' => '#FFFFFF'];
        
        $primaryRgb = $this->hexToRgb($colors['primary']);
        $backgroundRgb = $this->hexToRgb($colors['secondary']);

        return QrCodeGenerator::format('svg')
            ->size(500)
            ->margin(1)
            ->color($primaryRgb[0], $primaryRgb[1], $primaryRgb[2])
            ->backgroundColor($backgroundRgb[0], $backgroundRgb[1], $backgroundRgb[2])
            ->errorCorrection('H')
            ->generate($content);
    }

    /**
     * Convert hex color to RGB array
     */
    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Get QR code preview (base64 encoded)
     */
    public function getPreview(string $type, array $data, ?array $colors = null): string
    {
        $content = $this->generateQrContent($type, $data);
        $primaryColor = $colors['primary'] ?? '#000000';
        $backgroundColor = $colors['secondary'] ?? '#FFFFFF';
        
        $primaryRgb = $this->hexToRgb($primaryColor);
        $backgroundRgb = $this->hexToRgb($backgroundColor);

        $qrImage = QrCodeGenerator::format('png')
            ->size(300)
            ->margin(1)
            ->color($primaryRgb[0], $primaryRgb[1], $primaryRgb[2])
            ->backgroundColor($backgroundRgb[0], $backgroundRgb[1], $backgroundRgb[2])
            ->errorCorrection('H')
            ->generate($content);

        return 'data:image/png;base64,' . base64_encode($qrImage);
    }
}
