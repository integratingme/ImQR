<?php

namespace App\Services\Ai;

use App\Services\Ai\Contracts\AiProvider;
use App\Services\Ai\Contracts\ImageProvider;
use App\Services\Ai\Providers\AnthropicProvider;
use App\Services\Ai\Providers\OpenAiImageProvider;
use App\Services\Ai\Providers\OpenAiProvider;
use App\Services\Ai\PromptBuilder;
use App\Services\Ai\ImagePromptBuilder;
use App\Services\Ai\TemplateValidator;

class AiFrameGeneratorService
{
    public function __construct(
        private readonly PromptBuilder       $promptBuilder,
        private readonly ImagePromptBuilder  $imagePromptBuilder,
        private readonly TemplateValidator   $validator,
    ) {}

    /**
     * Generate a frame template using the specified provider.
     *
     * Returns an array with:
     *   'template' => [ 'background' => …, 'layers' => […] ]
     *   'provider' => 'openai' | 'anthropic'
     *   'model'    => '<model-name>'
     */
    public function generate(
        string $prompt,
        int $width,
        int $height,
        string $providerKey,
        bool $generateImage = false
    ): array {
        $provider = $this->resolveProvider($providerKey);

        $raw      = $provider->generateTemplate($prompt, $width, $height);
        $template = $this->validator->validate($raw, $width, $height);

        // Optionally generate background image if requested.
        if ($generateImage && $providerKey === 'openai') {
            $imageProvider = new OpenAiImageProvider();
            $imagePrompt   = $this->imagePromptBuilder->buildBackgroundImagePrompt($prompt, $width, $height);
            $backgroundImageDataUrl = $imageProvider->generateImage($imagePrompt, $width, $height);

            // Inject the generated image into the template as background_image.
            $template['background_image'] = $backgroundImageDataUrl;
            $template['background_image_fit'] = 'cover';
        }

        return [
            'template' => $template,
            'provider' => $provider->providerName(),
            'model'    => $provider->modelName(),
        ];
    }

    private function resolveProvider(string $key): AiProvider
    {
        return match ($key) {
            'openai'    => new OpenAiProvider($this->promptBuilder),
            'anthropic' => new AnthropicProvider($this->promptBuilder),
            default     => throw new \InvalidArgumentException("Unknown AI provider: {$key}"),
        };
    }
}
