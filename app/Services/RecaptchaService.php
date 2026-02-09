<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(
        private readonly ?string $secret = null,
        private readonly float $minScore = 0.5
    ) {
        // Constructor params default to config if not provided
    }

    public static function fromConfig(): self
    {
        return new self(
            config('services.recaptcha.secret'),
            (float) config('services.recaptcha.min_score', 0.5)
        );
    }

    /**
     * Verify reCAPTCHA v3 token and return whether it passes the minimum score.
     */
    public function verify(string $token, ?string $expectedAction = null): bool
    {
        if (empty($this->secret) || empty($token)) {
            return false;
        }

        try {
            $response = Http::asForm()->post(self::VERIFY_URL, [
                'secret' => $this->secret,
                'response' => $token,
            ]);

            if (!$response->successful()) {
                Log::warning('reCAPTCHA verify request failed', ['status' => $response->status()]);
                return false;
            }

            $body = $response->json();
            $success = (bool) ($body['success'] ?? false);
            $score = (float) ($body['score'] ?? 0);
            $action = $body['action'] ?? null;

            if (!$success) {
                Log::debug('reCAPTCHA verify failed', ['error_codes' => $body['error-codes'] ?? []]);
                return false;
            }

            if ($expectedAction !== null && $action !== $expectedAction) {
                Log::debug('reCAPTCHA action mismatch', ['expected' => $expectedAction, 'got' => $action]);
                return false;
            }

            return $score >= $this->minScore;
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA verify exception', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
