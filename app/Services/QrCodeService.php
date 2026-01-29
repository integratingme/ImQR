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
    public function generate(string $type, array $data, ?array $colors = null, ?array $customization = null): QrCode
    {
        // Generate QR code content based on type
        $qrContent = $this->generateQrContent($type, $data);
        
        // Create QR code record
        $qrCode = QrCode::create([
            'type' => $type,
            'name' => $data['name'] ?? 'Untitled QR Code',
            'data' => $data,
            'colors' => $colors ?? ['primary' => '#000000', 'secondary' => '#FFFFFF'],
            'customization' => $customization ?? $this->getDefaultCustomization(),
        ]);

        // Generate and save QR code image
        $this->generateAndSaveImage($qrCode, $qrContent, $colors, $customization);

        return $qrCode;
    }

    /**
     * Get default customization options
     */
    protected function getDefaultCustomization(): array
    {
        return [
            'pattern' => 'square', // square, circle, rounded
            'corner_style' => 'square', // square, rounded, extra-rounded
            'corner_dot_style' => 'square', // square, circle, rounded
        ];
    }

    /**
     * Generate QR code content based on type
     */
    protected function generateQrContent(string $type, array $data): string
    {
        return match($type) {
            'url' => $data['url'] ?? '',
            'email' => $this->generateEmailContent($data),
            'text' => $this->generateTextContent($data),
            'pdf' => $this->generatePdfContent($data),
            'menu' => $data['menu_url'] ?? $data['menu_file_url'] ?? '',
            'coupon' => $this->generateCouponContent($data),
            'event' => $this->generateEventContent($data),
            'app' => $this->generateAppContent($data),
            'location' => $this->generateLocationContent($data),
            'wifi' => $this->generateWifiContent($data),
            'phone' => $this->generatePhoneContent($data),
            'mp3' => $data['mp3_url'] ?? '',
            default => '',
        };
    }

    /**
     * Generate PDF QR content (URL to PDF page)
     */
    protected function generatePdfContent(array $data): string
    {
        // If pdf_page_url exists, use it (for existing QR codes)
        if (isset($data['pdf_page_url']) && !empty($data['pdf_page_url'])) {
            return $data['pdf_page_url'];
        }
        
        // For preview purposes, use a placeholder URL
        // This will be replaced with actual URL when QR code is saved
        return url('/pdf/preview');
    }

    /**
     * Generate Text QR content (URL to text page)
     */
    protected function generateTextContent(array $data): string
    {
        // If text_page_url exists, use it (for existing QR codes)
        if (isset($data['text_page_url']) && !empty($data['text_page_url'])) {
            return $data['text_page_url'];
        }
        
        // For preview purposes, use a placeholder URL
        // This will be replaced with actual URL when QR code is saved
        return url('/text/preview');
    }

    /**
     * Generate App QR content (URL to app page)
     */
    protected function generateAppContent(array $data): string
    {
        // If app_page_url exists, use it (for existing QR codes)
        if (isset($data['app_page_url']) && !empty($data['app_page_url'])) {
            return $data['app_page_url'];
        }
        
        // For preview purposes, use a placeholder URL
        // This will be replaced with actual URL when QR code is saved
        return url('/app/preview');
    }

    /**
     * Generate Coupon QR content (URL to coupon page)
     */
    protected function generateCouponContent(array $data): string
    {
        if (isset($data['coupon_page_url']) && !empty($data['coupon_page_url'])) {
            return $data['coupon_page_url'];
        }
        return url('/coupon/preview');
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
    protected function generateAndSaveImage(QrCode $qrCode, string $content, ?array $colors = null, ?array $customization = null): void
    {
        $primaryColor = $colors['primary'] ?? '#000000';
        $backgroundColor = $colors['secondary'] ?? '#FFFFFF';
        $customization = $customization ?? $qrCode->customization ?? $this->getDefaultCustomization();

        // Convert hex to RGB
        $primaryRgb = $this->hexToRgb($primaryColor);
        $backgroundRgb = $this->hexToRgb($backgroundColor);

        // Generate QR code as PNG
        // Note: For advanced customization (patterns, corners), we would need SVG manipulation
        // For now, we generate PNG directly - customization can be added later via SVG processing
        $qrImage = QrCodeGenerator::format('png')
            ->size(500)
            ->margin(1)
            ->color($primaryRgb[0], $primaryRgb[1], $primaryRgb[2])
            ->backgroundColor($backgroundRgb[0], $backgroundRgb[1], $backgroundRgb[2])
            ->errorCorrection('H')
            ->generate($content);

        // TODO: Apply customization via SVG manipulation if needed
        // For now, customization options are stored but not yet applied to the image

        // Save to storage
        $filename = 'qr-codes/' . $qrCode->id . '_' . time() . '.png';
        Storage::disk('public')->put($filename, $qrImage);

        // Update QR code record
        $qrCode->update(['qr_image_path' => $filename]);
    }

    /**
     * Apply customization to SVG QR code
     */
    protected function applyCustomization(string $svg, array $customization, string $primaryColor, string $backgroundColor): string
    {
        // For now, return the SVG as-is
        // TODO: Implement pattern, corner style, and corner dot style modifications
        // This would involve parsing the SVG and modifying the shapes
        // Patterns: square, circle, rounded
        // Corner styles: square, rounded, extra-rounded  
        // Corner dot styles: square, circle, rounded
        
        return $svg;
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
    public function regenerateQrCode(QrCode $qrCode, ?array $colors = null, ?array $customization = null): void
    {
        // Refresh the model to get latest data
        $qrCode->refresh();
        
        // Use provided colors or get from QR code
        $colors = $colors ?? $qrCode->colors ?? ['primary' => '#000000', 'secondary' => '#FFFFFF'];
        $customization = $customization ?? $qrCode->customization ?? $this->getDefaultCustomization();
        
        // Generate QR code content based on updated data
        $qrContent = $this->generateQrContent($qrCode->type, $qrCode->data);
        
        // For PDF type, ensure pdf_page_url is set
        if ($qrCode->type === 'pdf' && empty($qrContent)) {
            $data = $qrCode->data ?? [];
            if (isset($data['pdf_page_url'])) {
                $qrContent = $data['pdf_page_url'];
            } else {
                $data['pdf_page_url'] = route('qr-codes.pdf-page', $qrCode->id);
                $qrCode->update(['data' => $data]);
                $qrContent = $data['pdf_page_url'];
            }
        }
        
        // Generate and save QR code image
        $this->generateAndSaveImage($qrCode, $qrContent, $colors, $customization);
    }

    /**
     * Generate QR code in SVG format
     */
    public function generateSvg(QrCode $qrCode): string
    {
        $content = $this->generateQrContent($qrCode->type, $qrCode->data);
        $colors = $qrCode->colors ?? ['primary' => '#000000', 'secondary' => '#FFFFFF'];
        $customization = $qrCode->customization ?? $this->getDefaultCustomization();
        
        $primaryRgb = $this->hexToRgb($colors['primary']);
        $backgroundRgb = $this->hexToRgb($colors['secondary']);

        $svg = QrCodeGenerator::format('svg')
            ->size(500)
            ->margin(1)
            ->color($primaryRgb[0], $primaryRgb[1], $primaryRgb[2])
            ->backgroundColor($backgroundRgb[0], $backgroundRgb[1], $backgroundRgb[2])
            ->errorCorrection('H')
            ->generate($content);

        // Apply customization
        return $this->applyCustomization($svg, $customization, $colors['primary'], $colors['secondary']);
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
    public function getPreview(string $type, array $data, ?array $colors = null, ?array $customization = null): string
    {
        $content = $this->generateQrContent($type, $data);
        $primaryColor = $colors['primary'] ?? '#000000';
        $backgroundColor = $colors['secondary'] ?? '#FFFFFF';
        $customization = $customization ?? $this->getDefaultCustomization();
        
        $primaryRgb = $this->hexToRgb($primaryColor);
        $backgroundRgb = $this->hexToRgb($backgroundColor);

        // Generate as SVG first for customization
        $svg = QrCodeGenerator::format('svg')
            ->size(300)
            ->margin(1)
            ->color($primaryRgb[0], $primaryRgb[1], $primaryRgb[2])
            ->backgroundColor($backgroundRgb[0], $backgroundRgb[1], $backgroundRgb[2])
            ->errorCorrection('H')
            ->generate($content);

        // Apply customization
        $customizedSvg = $this->applyCustomization($svg, $customization, $primaryColor, $backgroundColor);

        // For preview, return SVG as data URI (browsers can display SVG directly)
        return 'data:image/svg+xml;base64,' . base64_encode($customizedSvg);
    }
}
