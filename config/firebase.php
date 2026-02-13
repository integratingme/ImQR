<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firebase Configuration
    |--------------------------------------------------------------------------
    |
    | These values are used for Firebase Authentication integration.
    | The client-side config is passed to the Firebase JS SDK.
    | The project_id is used server-side for token verification.
    |
    */

    'project_id' => env('FIREBASE_PROJECT_ID', ''),

    // Client-side Firebase config (passed to JS via Blade)
    'client' => [
        'api_key' => env('FIREBASE_API_KEY', ''),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN', ''),
        'project_id' => env('FIREBASE_PROJECT_ID', ''),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', ''),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', ''),
        'app_id' => env('FIREBASE_APP_ID', ''),
        'measurement_id' => env('FIREBASE_MEASUREMENT_ID', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Public Keys URL
    |--------------------------------------------------------------------------
    |
    | URL to fetch Google's public keys for verifying Firebase ID tokens.
    | These keys are cached based on their Cache-Control header.
    |
    */

    'google_public_keys_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com',

    /*
    |--------------------------------------------------------------------------
    | Token Verification Cache
    |--------------------------------------------------------------------------
    |
    | How long (in seconds) to cache Google's public keys.
    | Default: 3600 (1 hour). Google's keys typically rotate every ~6 hours.
    |
    */

    'public_keys_cache_ttl' => env('FIREBASE_KEYS_CACHE_TTL', 3600),

];
