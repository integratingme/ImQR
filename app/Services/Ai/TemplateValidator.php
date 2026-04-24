<?php

namespace App\Services\Ai;

use App\Services\Ai\Exceptions\AiInvalidOutputException;

/**
 * Validates and sanitises the template object returned by an AI provider.
 *
 * The expected format is the same structure used by TEMPLATE_LIBRARY in frame-editor.js:
 *   [
 *     'background' => '#rrggbb',
 *     'layers'     => [
 *       ['type' => 'rect',   'options' => ['left'=>…, 'top'=>…, 'width'=>…, 'height'=>…, …]],
 *       ['type' => 'circle', 'options' => ['left'=>…, 'top'=>…, 'radius'=>…, …]],
 *       ['type' => 'text',   'options' => ['left'=>…, 'top'=>…, 'text'=>…, 'fontSize'=>…, …]],
 *     ],
 *   ]
 */
class TemplateValidator
{
    private const ALLOWED_TYPES = ['rect', 'circle', 'text'];
    private const MAX_LAYERS    = 20;

    /**
     * Validate the raw template array returned by the AI provider.
     * Returns a sanitised copy on success, throws on failure.
     *
     * @throws AiInvalidOutputException
     */
    public function validate(array $template, int $width, int $height): array
    {
        // ── background ────────────────────────────────────────────────────────
        $background = $template['background'] ?? '#ffffff';
        if (! is_string($background)) {
            $background = '#ffffff';
        }
        if (! $this->isValidColor($background)) {
            $background = '#ffffff';
        }

        // ── background_image (optional) ───────────────────────────────────────
        $backgroundImage = null;
        $backgroundImageFit = 'cover';

        if (isset($template['background_image']) && is_string($template['background_image'])) {
            $bgImg = $template['background_image'];
            // Accept data URLs or http(s) URLs.
            if (str_starts_with($bgImg, 'data:image/') || filter_var($bgImg, FILTER_VALIDATE_URL)) {
                $backgroundImage = $bgImg;
            }
        }

        if (isset($template['background_image_fit']) && in_array($template['background_image_fit'], ['cover', 'contain'], true)) {
            $backgroundImageFit = $template['background_image_fit'];
        }

        // ── layers ────────────────────────────────────────────────────────────
        if (! isset($template['layers']) || ! is_array($template['layers'])) {
            throw new AiInvalidOutputException('AI output is missing a valid "layers" array.');
        }

        $rawLayers = array_slice($template['layers'], 0, self::MAX_LAYERS);

        $layers = [];
        foreach ($rawLayers as $index => $layerDef) {
            if (! is_array($layerDef)) {
                continue;
            }

            $type    = $layerDef['type'] ?? null;
            $options = $layerDef['options'] ?? null;

            if (! in_array($type, self::ALLOWED_TYPES, true)) {
                continue;
            }

            if (! is_array($options)) {
                continue;
            }

            try {
                $sanitised = $this->sanitiseLayer($type, $options, $width, $height);
                $layers[]  = ['type' => $type, 'options' => $sanitised];
            } catch (AiInvalidOutputException) {
                // Skip invalid layer rather than failing the whole template.
                continue;
            }
        }

        if (count($layers) === 0) {
            throw new AiInvalidOutputException('AI output contained no valid layers after validation.');
        }

        $result = [
            'background' => $background,
            'layers'     => $layers,
        ];

        if ($backgroundImage !== null) {
            $result['background_image']     = $backgroundImage;
            $result['background_image_fit'] = $backgroundImageFit;
        }

        return $result;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ──────────────────────────────────────────────────────────────────────────

    private function sanitiseLayer(string $type, array $options, int $width, int $height): array
    {
        return match ($type) {
            'rect'   => $this->sanitiseRect($options, $width, $height),
            'circle' => $this->sanitiseCircle($options, $width, $height),
            'text'   => $this->sanitiseText($options, $width, $height),
        };
    }

    private function sanitiseRect(array $o, int $width, int $height): array
    {
        $left   = $this->clampNum($o['left']   ?? 0, 0, $width);
        $top    = $this->clampNum($o['top']    ?? 0, 0, $height);
        $w      = $this->clampNum($o['width']  ?? 100, 1, $width);
        $h      = $this->clampNum($o['height'] ?? 100, 1, $height);
        $rx     = $this->clampNum($o['rx']     ?? 0, 0, min($w / 2, $h / 2));
        $ry     = $this->clampNum($o['ry']     ?? 0, 0, min($w / 2, $h / 2));

        return [
            'left'        => $left,
            'top'         => $top,
            'width'       => $w,
            'height'      => $h,
            'rx'          => $rx,
            'ry'          => $ry,
            'fill'        => $this->sanitiseColor($o['fill']   ?? 'transparent'),
            'stroke'      => $this->sanitiseColorOrNull($o['stroke'] ?? null),
            'strokeWidth' => $this->clampNum($o['strokeWidth'] ?? 0, 0, 30),
            'opacity'     => $this->clampFloat($o['opacity']   ?? 1, 0, 1),
        ];
    }

    private function sanitiseCircle(array $o, int $width, int $height): array
    {
        $left   = $this->clampNum($o['left']   ?? 0, 0, $width);
        $top    = $this->clampNum($o['top']    ?? 0, 0, $height);
        $radius = $this->clampNum($o['radius'] ?? 40, 1, min($width, $height));

        return [
            'left'        => $left,
            'top'         => $top,
            'radius'      => $radius,
            'fill'        => $this->sanitiseColor($o['fill']   ?? '#cccccc'),
            'stroke'      => $this->sanitiseColorOrNull($o['stroke'] ?? null),
            'strokeWidth' => $this->clampNum($o['strokeWidth'] ?? 0, 0, 30),
            'opacity'     => $this->clampFloat($o['opacity']   ?? 1, 0, 1),
        ];
    }

    private function sanitiseText(array $o, int $width, int $height): array
    {
        $text = is_string($o['text'] ?? null) ? mb_substr($o['text'], 0, 200) : 'SCAN ME';
        if (trim($text) === '') {
            $text = 'SCAN ME';
        }

        $allowedWeights = ['normal', '100', '200', '300', '400', '500', '600', '700', '800', '900', 'bold'];
        $fontWeight     = in_array((string) ($o['fontWeight'] ?? 'normal'), $allowedWeights, true)
            ? (string) $o['fontWeight']
            : 'normal';

        return [
            'left'       => $this->clampNum($o['left']       ?? 0, 0, $width),
            'top'        => $this->clampNum($o['top']        ?? 0, 0, $height),
            'text'       => $text,
            'fontSize'   => $this->clampNum($o['fontSize']   ?? 20, 8, 200),
            'fontFamily' => is_string($o['fontFamily'] ?? null) ? preg_replace('/[^A-Za-z0-9 \-]/', '', $o['fontFamily']) : 'Arial',
            'fontWeight' => $fontWeight,
            'fill'       => $this->sanitiseColor($o['fill']  ?? '#000000'),
            'opacity'    => $this->clampFloat($o['opacity']  ?? 1, 0, 1),
        ];
    }

    private function isValidColor(string $value): bool
    {
        return (bool) preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)
            || strtolower($value) === 'transparent';
    }

    private function sanitiseColor(mixed $value): string
    {
        if (is_string($value) && $this->isValidColor($value)) {
            return $value;
        }
        return 'transparent';
    }

    private function sanitiseColorOrNull(mixed $value): ?string
    {
        if ($value === null || $value === 'null' || $value === '') {
            return null;
        }
        return $this->sanitiseColor($value);
    }

    private function clampNum(mixed $value, float $min, float $max): float
    {
        $num = is_numeric($value) ? (float) $value : $min;
        return max($min, min($max, $num));
    }

    private function clampFloat(mixed $value, float $min, float $max): float
    {
        return $this->clampNum($value, $min, $max);
    }
}
