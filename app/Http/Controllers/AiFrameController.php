<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiGenerateFrameRequest;
use App\Services\Ai\AiFrameGeneratorService;
use App\Services\Ai\Exceptions\AiInvalidOutputException;
use App\Services\Ai\Exceptions\AiProviderException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;

class AiFrameController extends Controller
{
    public function __construct(private readonly AiFrameGeneratorService $generator) {}

    public function generate(AiGenerateFrameRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $key    = "ai-frame-generate:{$userId}";

        $maxAttempts  = (int) config('ai.rate_limit.max_attempts', 10);
        $decaySeconds = (int) config('ai.rate_limit.decay_minutes', 1) * 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'error' => "Too many requests. Please wait {$seconds} seconds before trying again.",
            ], 429);
        }

        RateLimiter::hit($key, $decaySeconds);

        try {
            $result = $this->generator->generate(
                prompt:        $request->string('prompt')->toString(),
                width:         (int) $request->input('width'),
                height:        (int) $request->input('height'),
                providerKey:   $request->string('provider')->toString(),
                generateImage: (bool) $request->input('generate_image', false),
            );
        } catch (AiInvalidOutputException $e) {
            return response()->json([
                'error' => 'The AI returned an invalid frame design. Please try a different prompt.',
            ], 422);
        } catch (AiProviderException $e) {
            return response()->json([
                'error' => 'The AI provider is currently unavailable. Please try again shortly.',
            ], 502);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json([
            'template' => $result['template'],
            'provider' => $result['provider'],
            'model'    => $result['model'],
        ]);
    }
}
