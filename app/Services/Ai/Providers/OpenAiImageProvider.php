<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\ImageProvider;
use App\Services\Ai\Exceptions\AiInvalidOutputException;
use App\Services\Ai\Exceptions\AiProviderException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiImageProvider implements ImageProvider
{
    public function generateImage(string $prompt, int $width, int $height): string
    {
        $apiKey  = config('ai.openai.api_key');
        $baseUrl = rtrim(config('ai.openai.base_url', 'https://api.openai.com/v1'), '/');
        $timeout = (int) config('ai.openai.image_timeout', 90);

        if (empty($apiKey)) {
            throw new AiProviderException('OpenAI API key is not configured.');
        }

        // DALL-E 3 only supports 1024×1024, 1024×1792, 1792×1024 as of 2024.
        // We'll use 1024×1024 and let the client resize/crop.
        $size = '1024x1024';

        $payload = [
            'model'           => 'dall-e-3',
            'prompt'          => $prompt,
            'n'               => 1,
            'size'            => $size,
            'response_format' => 'b64_json', // Returns base64 directly
            'quality'         => 'standard',
        ];

        $response = $this->sendWithRetry("{$baseUrl}/images/generations", $apiKey, $payload, $timeout);

        $b64 = $response['data'][0]['b64_json'] ?? null;

        if (! is_string($b64) || trim($b64) === '') {
            throw new AiInvalidOutputException('OpenAI image generation returned no base64 data.');
        }

        return 'data:image/png;base64,' . $b64;
    }

    public function providerName(): string
    {
        return 'openai';
    }

    private function sendWithRetry(string $url, string $apiKey, array $payload, int $timeout): array
    {
        $attempt = 0;

        while ($attempt < 2) {
            $attempt++;

            try {
                $response = Http::withToken($apiKey)
                    ->timeout($timeout)
                    ->post($url, $payload);
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                if ($attempt < 2) {
                    sleep(2);
                    continue;
                }
                throw new AiProviderException("OpenAI image connection failed: {$e->getMessage()}", 0, $e);
            }

            if ($response->successful()) {
                return $response->json();
            }

            $status = $response->status();

            if ($attempt < 2 && ($status >= 500 || $status === 429)) {
                sleep(2);
                continue;
            }

            $body = $response->body();
            Log::error('OpenAI image generation error', ['status' => $status, 'body' => substr($body, 0, 500)]);
            throw new AiProviderException("OpenAI image API returned HTTP {$status}.");
        }

        throw new AiProviderException('OpenAI image request failed after retries.');
    }
}
