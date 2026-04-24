<?php

namespace App\Services\Ai;

class PromptBuilder
{
    /**
     * Build the system prompt that instructs the model to produce a frame template.
     */
    public function systemPrompt(): string
    {
        return <<<'SYSTEM'
You are an expert QR code frame designer. Your sole task is to output a valid JSON object describing a decorative frame layout for a QR code.

OUTPUT RULES — follow these exactly:
- Output ONLY the JSON object. No prose, no markdown, no explanations, no code fences.
- The JSON must be parseable by PHP's json_decode without errors.

SCHEMA — the root object must have exactly these keys:
{
  "background": "<hex color string, e.g. #ffffff>",
  "layers": [ <layer objects, see below> ]
}

LAYER TYPES allowed in "layers":
1. Rectangle:
   { "type": "rect", "options": { "left": <number>, "top": <number>, "width": <number>, "height": <number>, "rx": <number, corner radius>, "ry": <number, corner radius>, "fill": "<color or transparent>", "stroke": "<color or null>", "strokeWidth": <number>, "opacity": <0–1> } }

2. Circle:
   { "type": "circle", "options": { "left": <number>, "top": <number>, "radius": <number>, "fill": "<color>", "stroke": "<color or null>", "strokeWidth": <number>, "opacity": <0–1> } }

3. Text:
   { "type": "text", "options": { "left": <number>, "top": <number>, "text": "<string>", "fontSize": <number>, "fontFamily": "<sans-serif font name>", "fontWeight": "<normal or 700>", "fill": "<color>", "opacity": <0–1> } }

DESIGN CONSTRAINTS — you MUST respect these:
- The canvas is CANVAS_WIDTHpx wide and CANVAS_HEIGHTpx tall (values injected per request).
- Leave a clear square area in the CENTER of the canvas (roughly 40–55% of the smaller dimension) for the QR code. Do NOT place any layer that overlaps the central QR area.
- All layer coordinates (left, top, width, height, radius) must be within [0, CANVAS_WIDTH] × [0, CANVAS_HEIGHT].
- Use at most 12 layers total.
- Colors must be valid CSS hex strings (#rrggbb) or the string "transparent".
- Do not include the keys "background_image", "qr_zone", "version", or "canvas_width"/"canvas_height" — those are managed by the application.
SYSTEM;
    }

    /**
     * Build the user prompt combining the style description, canvas dimensions, and a concrete example.
     */
    public function userPrompt(string $styleDescription, int $width, int $height): string
    {
        $qrZone = $this->calculateDefaultQrZone($width, $height);
        $example = $this->exampleTemplate($width, $height, $qrZone);

        return <<<PROMPT
Canvas size: {$width}px wide × {$height}px tall.

QR CODE RESERVED ZONE — DO NOT OVERLAP:
  The QR code will occupy this exact area (already reserved, no layer should overlap):
  - X: {$qrZone['x']}px to {$qrZone['x_end']}px
  - Y: {$qrZone['y']}px to {$qrZone['y_end']}px
  - Size: {$qrZone['size']}px × {$qrZone['size']}px (centered)
  
  Keep ALL decorative elements (borders, text, shapes) OUTSIDE this zone for best scan reliability.

Design request: {$styleDescription}

Return ONLY a JSON object matching the schema.
Example of a valid output for a {$width}×{$height} canvas (for reference, do not copy colours/style):
{$example}
PROMPT;
    }

    /**
     * Calculate the default QR zone bounds (matching frame-editor.js getDefaultSquareQrZone logic).
     * Default: 56% of the shorter dimension, centered.
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

    /**
     * A minimal valid example adapted to the actual canvas size so the model can
     * understand the coordinate system without copying the style.
     */
    private function exampleTemplate(int $width, int $height, array $qrZone): string
    {
        $pad   = (int) round($width * 0.05);
        $rw    = $width - ($pad * 2);
        $rh    = $height - ($pad * 2);
        
        // Place text ABOVE the QR zone, safely away from it.
        $textX = (int) round($width * 0.25);
        $textY = max((int) round($height * 0.1), $pad + 20);
        
        // Make sure text Y doesn't overlap QR zone.
        if ($textY + 40 > $qrZone['y']) {
            $textY = $qrZone['y'] - 50;
        }

        $example = [
            'background' => '#f8fafc',
            'layers'     => [
                [
                    'type'    => 'rect',
                    'options' => [
                        'left'        => $pad,
                        'top'         => $pad,
                        'width'       => $rw,
                        'height'      => $rh,
                        'rx'          => 20,
                        'ry'          => 20,
                        'fill'        => 'transparent',
                        'stroke'      => '#334155',
                        'strokeWidth' => 3,
                        'opacity'     => 1,
                    ],
                ],
                [
                    'type'    => 'text',
                    'options' => [
                        'left'       => $textX,
                        'top'        => $textY,
                        'text'       => 'SCAN ME',
                        'fontSize'   => 28,
                        'fontFamily' => 'Arial',
                        'fontWeight' => '700',
                        'fill'       => '#0f172a',
                        'opacity'    => 1,
                    ],
                ],
            ],
        ];

        return json_encode($example, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
