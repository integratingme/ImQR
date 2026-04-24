<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\AiProvider;
use App\Services\Ai\Exceptions\AiInvalidOutputException;
use App\Services\Ai\Exceptions\AiProviderException;
use App\Services\Ai\PromptBuilder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiProvider implements AiProvider
{
    private string $model;

    public function __construct(private readonly PromptBuilder $promptBuilder)
    {
        $this->model = config('ai.openai.model', 'gpt-4o-mini');
    }

    public function generateTemplate(string $prompt, int $width, int $height): array
    {
        $apiKey  = config('ai.openai.api_key');
        $baseUrl = rtrim(config('ai.openai.base_url', 'https://api.openai.com/v1'), '/');
        $timeout = (int) config('ai.openai.timeout', 45);

        if (empty($apiKey)) {
            throw new AiProviderException('OpenAI API key is not configured.');
        }

        $systemPrompt = str_replace(
            ['CANVAS_WIDTH', 'CANVAS_HEIGHT'],
            [$width, $height],
            $this->promptBuilder->systemPrompt()
        );

        $payload = [
            'model'           => $this->model,
            'response_format' => ['type' => 'json_object'],
            'messages'        => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => $this->promptBuilder->userPrompt($prompt, $width, $height)],
            ],
            'temperature'     => 0.7,
            'max_tokens'      => 2048,
        ];

        $response = $this->sendWithRetry("{$baseUrl}/chat/completions", $apiKey, $payload, $timeout);

        $content = $response['choices'][0]['message']['content'] ?? null;

        if (! is_string($content) || trim($content) === '') {
            throw new AiInvalidOutputException('OpenAI returned an empty response content.');
        }

        return $this->parseJson($content);
    }

    public function providerName(): string
    {
        return 'openai';
    }

    public function modelName(): string
    {
        return $this->model;
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
                    continue;
                }
                throw new AiProviderException("OpenAI connection failed: {$e->getMessage()}", 0, $e);
            }

            if ($response->successful()) {
                return $response->json();
            }

            $status = $response->status();

            if ($attempt < 2 && ($status >= 500 || $status === 429)) {
                sleep(1);
                continue;
            }

            $body = $response->body();
            Log::error('OpenAI API error', ['status' => $status, 'body' => substr($body, 0, 500)]);
            throw new AiProviderException("OpenAI API returned HTTP {$status}.");
        }

        throw new AiProviderException('OpenAI request failed after retries.');
    }

    private function parseJson(string $content): array
    {
        $decoded = json_decode(trim($content), true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            throw new AiInvalidOutputException('OpenAI output could not be decoded as JSON.');
        }

        return $decoded;
    }
}
