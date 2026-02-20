<?php

namespace App\Services;

use App\Models\QrCode;
use App\Models\QrCodeFile;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class QrCodeService
{
    /**
     * Generate QR code based on type and data
     */
    public function generate(string $type, array $data, ?array $colors = null, ?array $customization = null, ?int $userId = null): QrCode
    {
        // Generate QR code content based on type
        $qrContent = $this->generateQrContent($type, $data);

        // For location type: always store the resolved URL in data so history/download can display the QR
        if ($type === 'location' && $qrContent !== '') {
            $data['location_url'] = $qrContent;
        }

        // Create QR code record
        $qrCode = QrCode::create([
            'type' => $type,
            'name' => $data['name'] ?? 'Untitled QR Code',
            'data' => $data,
            'colors' => $colors ?? ['primary' => '#000000', 'secondary' => '#FFFFFF'],
            'customization' => $customization ?? $this->getDefaultCustomization(),
            'user_id' => $userId,
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
            'menu' => $this->generateMenuContent($data),
            'coupon' => $this->generateCouponContent($data),
            'event' => $this->generateEventContent($data),
            'app' => $this->generateAppContent($data),
            'location' => $this->generateLocationContent($data),
            'wifi' => $this->generateWifiContent($data),
            'phone' => $this->generatePhoneContent($data),
            'business_card' => $this->generateBusinessCardContent($data),
            'personal_vcard' => $this->generatePersonalVCardContent($data),
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
     * Generate event QR content (URL to event page)
     */
    protected function generateEventContent(array $data): string
    {
        if (isset($data['event_page_url']) && !empty($data['event_page_url'])) {
            return $data['event_page_url'];
        }
        return url('/event/preview');
    }

    /**
     * Generate location QR content.
     * If location_url is provided (e.g. maps.app.goo.gl), use it as-is so the QR opens that link in the browser → Google Maps.
     * Otherwise use coordinates or address to build a maps URL.
     */
    protected function generateLocationContent(array $data): string
    {
        $locationUrl = trim((string) ($data['location_url'] ?? ''));
        if ($locationUrl !== '' && str_starts_with(strtolower($locationUrl), 'https://')) {
            return $locationUrl;
        }
        $lat = isset($data['latitude']) && $data['latitude'] !== '' ? (float) $data['latitude'] : null;
        $lng = isset($data['longitude']) && $data['longitude'] !== '' ? (float) $data['longitude'] : null;
        if ($lat !== null && $lng !== null) {
            return 'https://www.google.com/maps?q=' . $lat . ',' . $lng;
        }
        $address = $data['address'] ?? '';
        return 'https://www.google.com/maps?q=' . rawurlencode($address);
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
     * Generate phone QR content (URL to phone landing page; page has tel: link to call)
     */
    protected function generatePhoneContent(array $data): string
    {
        if (isset($data['phone_page_url']) && !empty($data['phone_page_url'])) {
            return $data['phone_page_url'];
        }
        return url('/phone/preview');
    }

    /**
     * Generate business card QR content (URL to business card page)
     */
    protected function generateBusinessCardContent(array $data): string
    {
        if (isset($data['business_card_page_url']) && !empty($data['business_card_page_url'])) {
            return $data['business_card_page_url'];
        }
        return url('/business-card/preview');
    }

    /**
     * Generate personal vCard QR content (URL to vCard page)
     */
    protected function generatePersonalVCardContent(array $data): string
    {
        if (isset($data['personal_vcard_page_url']) && !empty($data['personal_vcard_page_url'])) {
            return $data['personal_vcard_page_url'];
        }
        return url('/vcard/preview');
    }

    /**
     * Generate menu QR content (URL to menu page; use placeholder when not yet saved)
     */
    protected function generateMenuContent(array $data): string
    {
        if (isset($data['menu_page_url']) && !empty($data['menu_page_url'])) {
            return $data['menu_page_url'];
        }
        if (!empty($data['menu_url'] ?? '')) {
            return $data['menu_url'];
        }
        if (!empty($data['menu_file_url'] ?? '')) {
            return $data['menu_file_url'];
        }
        return url('/menu/preview');
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
     * Allowed extensions per category (whitelist only; never use client name for path).
     */
    private const EXTENSION_WHITELIST = [
        'pdf' => ['pdf'],
        'image' => ['jpg', 'jpeg', 'png'],
    ];

    /**
     * Handle file upload. Stores file with a generated safe name (UUID + whitelisted extension)
     * to avoid path traversal, .htaccess overwrite, special chars, null byte injection.
     */
    public function handleFileUpload(QrCode $qrCode, UploadedFile $file, string $fileType): QrCodeFile
    {
        $safeExtension = $this->getSafeExtension($file, $fileType);
        $safeName = Str::uuid()->toString() . '.' . $safeExtension;
        $directory = 'qr-files';
        $path = $file->storeAs($directory, $safeName, 'public');

        $originalName = $this->sanitizeOriginalName($file->getClientOriginalName(), $safeExtension);

        return QrCodeFile::create([
            'qr_code_id' => $qrCode->id,
            'file_type' => $fileType,
            'file_path' => $path,
            'original_name' => $originalName,
            'file_size' => $file->getSize(),
        ]);
    }

    /**
     * Get a safe extension from whitelist based on file type (never trust client).
     */
    private function getSafeExtension(UploadedFile $file, string $fileType): string
    {
        $clientExt = strtolower($file->getClientOriginalExtension() ?? '');

        if (str_starts_with($fileType, 'menu_product_') || $fileType === 'review_frame_logo') {
            $allowed = self::EXTENSION_WHITELIST['image'];
        } elseif ($fileType === 'pdf' || $fileType === 'menu') {
            $allowed = self::EXTENSION_WHITELIST['pdf'];
        } else {
            $allowed = self::EXTENSION_WHITELIST['image'];
        }

        return in_array($clientExt, $allowed, true) ? $clientExt : $allowed[0];
    }

    /**
     * Sanitize original name for display/download only (never used for path).
     */
    private function sanitizeOriginalName(string $name, string $fallbackExtension): string
    {
        $name = str_replace(["\0", "\r", "\n"], '', $name);
        $name = basename($name);
        $name = preg_replace('/[^\w\s.\-]/u', '_', $name) ?? $name;
        $name = trim($name);
        if (strlen($name) > 200) {
            $name = substr($name, 0, 200);
        }
        if ($name === '' || $name === '.') {
            return 'download.' . $fallbackExtension;
        }
        return $name;
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
     * Generate QR code in SVG format (basic, without frame)
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
     * Generate QR code in SVG format with frame and full styling
     * This method returns configuration data for client-side rendering
     */
    public function generateStyledSvg(QrCode $qrCode): string
    {
        // For styled SVG with frames, we need to generate it client-side
        // Return the basic SVG for now (frames are handled client-side)
        return $this->generateSvg($qrCode);
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
    
    /**
     * Get frame configuration for a given frame ID
     */
    public function getFrameConfig(string $frameId): ?array
    {
        $frames = [
            'standard-border' => [
                'url' => 'frames/standard-border.svg',
                'qrLeft' => 5, 'qrTop' => 4, 'qrWidth' => 90, 'qrHeight' => 72,
                'frameWidth' => 400, 'frameHeight' => 500,
                'themable' => true
            ],
            'menu-qr' => [
                'url' => 'frames/menu-qr.svg',
                'qrLeft' => 5, 'qrTop' => 4, 'qrWidth' => 90, 'qrHeight' => 72,
                'frameWidth' => 400, 'frameHeight' => 500,
                'themable' => true
            ],
            'location' => [
                'url' => 'frames/location.svg',
                'qrLeft' => 5, 'qrTop' => 4, 'qrWidth' => 90, 'qrHeight' => 72,
                'frameWidth' => 400, 'frameHeight' => 500,
                'themable' => true
            ],
            'wifi' => [
                'url' => 'frames/wifi.svg',
                'qrLeft' => 5, 'qrTop' => 4, 'qrWidth' => 90, 'qrHeight' => 72,
                'frameWidth' => 400, 'frameHeight' => 500,
                'themable' => true
            ],
            'chat' => [
                'url' => 'frames/chat.svg',
                'qrLeft' => 5, 'qrTop' => 4, 'qrWidth' => 90, 'qrHeight' => 72,
                'frameWidth' => 400, 'frameHeight' => 500,
                'themable' => true
            ],
            'coupon' => [
                'url' => 'frames/coupon.svg',
                'qrLeft' => 5, 'qrTop' => 4, 'qrWidth' => 90, 'qrHeight' => 72,
                'frameWidth' => 400, 'frameHeight' => 500,
                'themable' => true
            ],
            'review-us' => [
                'url' => 'frames/review-us.svg',
                'qrLeft' => 15, 'qrTop' => 6.15, 'qrWidth' => 70, 'qrHeight' => 43.08,
                'frameWidth' => 400, 'frameHeight' => 650
            ]
        ];
        
        return $frames[$frameId] ?? null;
    }
}
