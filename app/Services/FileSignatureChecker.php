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

    /** ID3 tag (MP3 with ID3v2) */
    private const MP3_ID3_SIGNATURE = '494433';

    /** MPEG frame sync (MP3 without ID3: FF FB, FF FA, FF F3, etc.) */
    private const MP3_FRAME_SYNC = 'ff';

    /** M4A/MP4: "ftyp" at offset 4 (66 74 79 70) */
    private const M4A_FTYP_OFFSET = 4;
    private const M4A_FTYP_SIGNATURE = '66747970';

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

    /**
     * Check if file has valid MP3 signature (ID3 tag or MPEG frame sync).
     */
    public static function hasValidMp3Signature(UploadedFile $file): bool
    {
        $path = $file->getRealPath();
        if (!$path || !is_readable($path)) {
            return false;
        }
        $bytes = @file_get_contents($path, false, null, 0, 4);
        if ($bytes === false || strlen($bytes) < 2) {
            return false;
        }
        $hex = bin2hex($bytes);

        // ID3v2 at start
        if (str_starts_with($hex, self::MP3_ID3_SIGNATURE)) {
            return true;
        }
        // MPEG frame sync: FF E0–FF FF (first byte FF, second byte >= E0 in hex)
        if (strlen($hex) >= 4 && substr($hex, 0, 2) === self::MP3_FRAME_SYNC) {
            $second = (int) substr($hex, 2, 2);
            if ($second >= 0xe0 && $second <= 0xff) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if file has valid M4A/MP4 signature ("ftyp" at offset 4).
     */
    public static function hasValidM4aSignature(UploadedFile $file): bool
    {
        $path = $file->getRealPath();
        if (!$path || !is_readable($path)) {
            return false;
        }
        $length = self::M4A_FTYP_OFFSET + 4;
        $bytes = @file_get_contents($path, false, null, 0, $length);
        if ($bytes === false || strlen($bytes) < $length) {
            return false;
        }
        $atOffset = substr($bytes, self::M4A_FTYP_OFFSET, 4);
        $hex = bin2hex($atOffset);

        return $hex === self::M4A_FTYP_SIGNATURE;
    }

    /**
     * Check if file has valid audio signature (MP3 or M4A).
     */
    public static function hasValidAudioSignature(UploadedFile $file): bool
    {
        return self::hasValidMp3Signature($file) || self::hasValidM4aSignature($file);
    }
}
