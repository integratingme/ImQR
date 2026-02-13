<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

/**
 * Validates file content by magic bytes (file signatures) to prevent
 * malicious files disguised by extension/MIME. Use as highest-priority check.
 */
class FileSignatureChecker
{
    /** JPEG: FF D8 FF */
    private const JPEG_SIGNATURE = 'ffd8ff';

    /** PNG: 89 50 4E 47 0D 0A 1A 0A */
    private const PNG_SIGNATURE = '89504e470d0a1a0a';

    /** PDF: %PDF- (25 50 44 46 2D) */
    private const PDF_SIGNATURE = '255044462d';


    /**
     * Check if file has valid image signature (JPEG or PNG only).
     */
    public static function hasValidImageSignature(UploadedFile $file): bool
    {
        $path = $file->getRealPath();
        if (!$path || !is_readable($path)) {
            return false;
        }
        $bytes = @file_get_contents($path, false, null, 0, 12);
        if ($bytes === false || strlen($bytes) < 3) {
            return false;
        }
        $hex = bin2hex($bytes);

        if (str_starts_with($hex, self::JPEG_SIGNATURE)) {
            return true;
        }
        if (str_starts_with($hex, self::PNG_SIGNATURE)) {
            return true;
        }

        return false;
    }

    /**
     * Check if file has valid PDF signature (%PDF-).
     */
    public static function hasValidPdfSignature(UploadedFile $file): bool
    {
        $path = $file->getRealPath();
        if (!$path || !is_readable($path)) {
            return false;
        }
        $bytes = @file_get_contents($path, false, null, 0, 8);
        if ($bytes === false || strlen($bytes) < 5) {
            return false;
        }
        $hex = bin2hex($bytes);

        return str_starts_with($hex, self::PDF_SIGNATURE);
    }

}
