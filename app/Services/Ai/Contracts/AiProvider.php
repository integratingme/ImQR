<?php

namespace App\Services\Ai\Contracts;

interface AiProvider
{
    /**
     * Generate a frame template for the given prompt and canvas dimensions.
     *
     * Returns an array in the TEMPLATE_LIBRARY format used by the JS editor:
     *   [
     *     'background' => '#ffffff',
     *     'layers'     => [
     *       ['type' => 'rect',   'options' => ['left'=>…, 'top'=>…, …]],
     *       ['type' => 'text',   'options' => ['left'=>…, 'top'=>…, 'text'=>…, …]],
     *       ['type' => 'circle', 'options' => ['left'=>…, 'top'=>…, 'radius'=>…, …]],
     *     ],
     *   ]
     *
     * @throws \App\Services\Ai\Exceptions\AiProviderException on provider-side failure
     * @throws \App\Services\Ai\Exceptions\AiInvalidOutputException when model output is unparseable/invalid
     */
    public function generateTemplate(string $prompt, int $width, int $height): array;

    /**
     * Return the canonical provider slug identifier ('openai' | 'anthropic' …).
     */
    public function providerName(): string;

    /**
     * Return the model identifier used for this request.
     */
    public function modelName(): string;
}
