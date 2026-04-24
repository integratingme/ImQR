<?php

namespace App\Services\Ai;

class ImagePromptBuilder
{
    /**
     * Build a DALL-E/image-gen prompt that creates a decorative background
     * with a clear center area reserved for the QR code.
     */
    public function buildBackgroundImagePrompt(string $styleDescription, int $width, int $height): string
    {
        $qrZone = $this->calculateDefaultQrZone($width, $height);

        return <<<PROMPT
Create a decorative frame background image for a QR code in the style: "{$styleDescription}".

CRITICAL REQUIREMENTS:
- Image dimensions: {$width}px × {$height}px
- Leave the CENTER SQUARE AREA ({$qrZone['x']}px–{$qrZone['x_end']}px horizontal, {$qrZone['y']}px–{$qrZone['y_end']}px vertical) COMPLETELY EMPTY or with a subtle transparent/white gradient fade so a QR code can be placed there.
- The decorative elements (patterns, borders, text, gradients, ornaments) should be positioned AROUND the edges and corners, NOT in the center.
- High quality, clean, modern aesthetic.
- No text mentioning "QR" or "scan" unless part of the style description.
PROMPT;
    }

    /**
     * Calculate the default QR zone bounds (matching PromptBuilder logic).
     */
    private function calculateDefaultQrZone(int $width, int $height): array
    {
        $shorter = min($width, $height);
        $size    = (int) round(($shorter * 56) / 100);
        $x       = (int) round(($width  - $size) / 2);
        $y       = (int) round(($height - $size) / 2);

        return [
            'x'     => $x,
            'y'     => $y,
            'size'  => $size,
            'x_end' => $x + $size,
            'y_end' => $y + $size,
        ];
    }
}
