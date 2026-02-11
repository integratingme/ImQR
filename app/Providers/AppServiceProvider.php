<?php

namespace App\Providers;

use App\Services\FileSignatureChecker;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerFileSignatureValidators();

        RateLimiter::for('qr-create', function (Request $request) {
            // Skip rate limiting in local development environment
            if (app()->environment('local')) {
                return Limit::none();
            }
            
            // Whitelist IP addresses that bypass rate limiting
            $whitelistIps = ['127.0.0.1', '::1'];
            $clientIp = $request->ip();
            
            // Skip rate limiting for whitelisted IPs
            if (in_array($clientIp, $whitelistIps)) {
                return Limit::none();
            }
            
            return Limit::perMinute(5)->by($clientIp);
        });

        RateLimiter::for('qr-create-daily', function (Request $request) {
            // Skip rate limiting in local development environment
            if (app()->environment('local')) {
                return Limit::none();
            }
            
            // Whitelist IP addresses that bypass rate limiting
            $whitelistIps = ['127.0.0.1', '::1'];
            $clientIp = $request->ip();
            
            // Skip rate limiting for whitelisted IPs
            if (in_array($clientIp, $whitelistIps)) {
                return Limit::none();
            }
            
            return Limit::perDay(30)->by($clientIp);
        });
    }

    /**
     * Register magic-bytes (file signature) validators for uploads.
     */
    private function registerFileSignatureValidators(): void
    {
        Validator::extend('image_signature', function (string $attribute, $value, array $parameters, $validator): bool {
            if (!$value instanceof \Illuminate\Http\UploadedFile) {
                return false;
            }
            return FileSignatureChecker::hasValidImageSignature($value);
        });

        Validator::extend('pdf_signature', function (string $attribute, $value, array $parameters, $validator): bool {
            if (!$value instanceof \Illuminate\Http\UploadedFile) {
                return false;
            }
            return FileSignatureChecker::hasValidPdfSignature($value);
        });

        Validator::extend('mp3_signature', function (string $attribute, $value, array $parameters, $validator): bool {
            if (!$value instanceof \Illuminate\Http\UploadedFile) {
                return false;
            }
            return FileSignatureChecker::hasValidMp3Signature($value);
        });

        Validator::extend('m4a_signature', function (string $attribute, $value, array $parameters, $validator): bool {
            if (!$value instanceof \Illuminate\Http\UploadedFile) {
                return false;
            }
            return FileSignatureChecker::hasValidM4aSignature($value);
        });

        Validator::extend('audio_signature', function (string $attribute, $value, array $parameters, $validator): bool {
            if (!$value instanceof \Illuminate\Http\UploadedFile) {
                return false;
            }
            return FileSignatureChecker::hasValidAudioSignature($value);
        });
    }
}
