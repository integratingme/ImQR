<?php

namespace App\Services\Ai\Contracts;

interface ImageProvider
{
    /**
     * Generate a background image from a text prompt.
     * Returns a data URL (data:image/png;base64,...) suitable for embedding in design_json.
     *
     * @throws \App\Services\Ai\Exceptions\AiProviderException on provider-side failure
     * @throws \App\Services\Ai\Exceptions\AiInvalidOutputException when output is invalid
     */
    public function generateImage(string $prompt, int $width, int $height): string;

    /**
     * Return the canonical provider slug identifier ('openai' | ...).
     */
    public function providerName(): string;
}
