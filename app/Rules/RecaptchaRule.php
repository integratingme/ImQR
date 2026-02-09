<?php

namespace App\Rules;

use App\Services\RecaptchaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RecaptchaRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!config('services.recaptcha.enabled')) {
            return;
        }

        $token = is_string($value) ? trim($value) : '';
        if ($token === '') {
            $fail('Please complete the security check and try again.');
            return;
        }

        $service = RecaptchaService::fromConfig();
        if (!$service->verify($token, 'submit')) {
            $fail('Security check failed. Please try again.');
        }
    }
}
