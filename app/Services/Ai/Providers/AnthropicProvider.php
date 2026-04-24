<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\AiProvider;
use App\Services\Ai\Exceptions\AiInvalidOutputException;
use App\Services\Ai\Exceptions\AiProviderException;
use App\Services\Ai\PromptBuilder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnthropicProvider implements AiProvider
{
    private string $model;

    public function __construct(private readonly PromptBuilder $promptBuilder)
    {
        $this->model = config('ai.anthropic.model', 'claude-3-5-sonnet-20241022');
    }

    public function generateTemplate(string $prompt, int $width, int $height): array
    {
        $apiKey  = config('ai.anthropic.api_key');
        $baseUrl = rtrim(config('ai.anthropic.base_url', 'https://api.anthropic.com'), '/');
        $version = config('ai.anthropic.version', '2023-06-01');
        $timeout = (int) config('ai.anthropic.timeout', 45);

        if (empty($apiKey)) {
            throw new AiProviderException('Anthropic API key is not configured.');
        }

        $systemPrompt = str_replace(
            ['CANVAS_WIDTH', 'CANVAS_HEIGHT'],
            [$width, $height],
            $this->promptBuilder->systemPrompt()
        );

        $payload = [
            'model'      => $this->model,
            'max_tokens' => 2048,
            'system'     => $systemPrompt,
            'messages'   => [
                ['role' => 'user', 'content' => $this->promptBuilder->userPrompt($prompt, $width, $height)],
            ],
        ];

        $response = $this->sendWithRetry("{$baseUrl}/v1/messages", $apiKey, $version, $payload, $timeout);

        $content = $response['content'][0]['text'] ?? null;

        if (! is_string($content) || trim($content) === '') {
            throw new AiInvalidOutputException('Anthropic returned an empty response content.');
        }

        return $this->parseJson($content);
    }

    public function providerName(): string
    {
        return 'anthropic';
    }

    public function modelName(): string
    {
        return $this->model;
    }

    private function sendWithRetry(string $url, string $apiKey, string $version, array $payload, int $timeout): array
    {
        $attempt = 0;

        while ($attempt < 2) {
            $attempt++;

            try {
                $response = Http::withHeaders([
                    'x-api-key'         => $apiKey,
                    'anthropic-version' => $version,
                    'content-type'      => 'application/json',
                ])
                    ->timeout($timeout)
                    ->post($url, $payload);
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                if ($attempt < 2) {
                    continue;
                }
                throw new AiProviderException("Anthropic connection failed: {$e->getMessage()}", 0, $e);
            }

            if ($response->successful()) {
                return $response->json();
            }

            $status = $response->status();

            if ($attempt < 2 && ($status >= 500 || $status === 529)) {
                sleep(1);
                continue;
            }

            $body = $response->body();
            Log::error('Anthropic API error', ['status' => $status, 'body' => substr($body, 0, 500)]);
            throw new AiProviderException("Anthropic API returned HTTP {$status}.");
        }

        throw new AiProviderException('Anthropic request failed after retries.');
    }

    private function parseJson(string $content): array
    {
        $text = trim($content);

        // Strip optional code fence wrapping that the model may add despite instructions.
        if (str_starts_with($text, '```')) {
            $text = preg_replace('/^```[a-z]*\n?/i', '', $text);
            $text = rtrim($text, '`');
        }

        $decoded = json_decode(trim($text), true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            throw new AiInvalidOutputException('Anthropic output could not be decoded as JSON.');
        }

        return $decoded;
    }
}
