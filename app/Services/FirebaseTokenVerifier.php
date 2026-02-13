<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use UnexpectedValueException;

class FirebaseTokenVerifier
{
    /**
     * The Firebase project ID.
     */
    protected string $projectId;

    /**
     * URL to fetch Google's public keys.
     */
    protected string $publicKeysUrl;

    /**
     * Cache TTL for public keys (seconds).
     */
    protected int $cacheTtl;

    public function __construct()
    {
        $this->projectId = config('firebase.project_id');
        $this->publicKeysUrl = config('firebase.google_public_keys_url');
        $this->cacheTtl = config('firebase.public_keys_cache_ttl', 3600);
    }

    /**
     * Verify a Firebase ID token and return the decoded payload.
     *
     * @param  string  $idToken  The Firebase ID token (JWT)
     * @return object  Decoded token payload
     *
     * @throws \UnexpectedValueException  If token is invalid
     */
    public function verify(string $idToken): object
    {
        $publicKeys = $this->getPublicKeys();

        // Build Key objects for each kid
        $keys = [];
        foreach ($publicKeys as $kid => $cert) {
            $keys[$kid] = new Key($cert, 'RS256');
        }

        // Decode and verify the JWT
        $decoded = JWT::decode($idToken, $keys);

        // Additional Firebase-specific claim validations
        $this->validateClaims($decoded);

        return $decoded;
    }

    /**
     * Validate Firebase-specific claims.
     *
     * @param  object  $decoded  The decoded JWT payload
     *
     * @throws \UnexpectedValueException
     */
    protected function validateClaims(object $decoded): void
    {
        $expectedIssuer = 'https://securetoken.google.com/' . $this->projectId;

        // Check issuer
        if (!isset($decoded->iss) || $decoded->iss !== $expectedIssuer) {
            throw new UnexpectedValueException(
                'Invalid token issuer. Expected: ' . $expectedIssuer
            );
        }

        // Check audience
        if (!isset($decoded->aud) || $decoded->aud !== $this->projectId) {
            throw new UnexpectedValueException(
                'Invalid token audience. Expected: ' . $this->projectId
            );
        }

        // Check subject (Firebase UID) is not empty
        if (empty($decoded->sub)) {
            throw new UnexpectedValueException('Token subject (uid) is empty.');
        }
    }

    /**
     * Fetch Google's public keys (cached).
     *
     * @return array<string, string>  Key ID => PEM certificate
     *
     * @throws \RuntimeException
     */
    protected function getPublicKeys(): array
    {
        return Cache::remember('firebase_public_keys', $this->cacheTtl, function () {
            $response = Http::get($this->publicKeysUrl);

            if (!$response->successful()) {
                throw new \RuntimeException(
                    'Failed to fetch Firebase public keys. Status: ' . $response->status()
                );
            }

            $keys = $response->json();

            if (empty($keys) || !is_array($keys)) {
                throw new \RuntimeException('Firebase public keys response is empty or invalid.');
            }

            return $keys;
        });
    }

    /**
     * Extract user information from a verified token payload.
     *
     * @param  object  $decoded  The decoded JWT payload
     * @return array{uid: string, email: ?string, name: ?string, phone: ?string, picture: ?string, email_verified: bool, provider: string}
     */
    public function extractUserInfo(object $decoded): array
    {
        $provider = 'password'; // default

        // Determine the sign-in provider
        if (isset($decoded->firebase->sign_in_provider)) {
            $provider = $decoded->firebase->sign_in_provider;
        }

        return [
            'uid' => $decoded->sub,
            'email' => $decoded->email ?? null,
            'name' => $decoded->name ?? null,
            'phone' => $decoded->phone_number ?? null,
            'picture' => $decoded->picture ?? null,
            'email_verified' => $decoded->email_verified ?? false,
            'provider' => $provider,
        ];
    }
}
