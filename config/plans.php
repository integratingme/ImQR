<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Plan Limits & Features
    |--------------------------------------------------------------------------
    |
    | Define limits and features for each plan tier.
    |
    */

    'guest' => [
        'scan_limit_per_code' => 10,
        'can_add_logo' => false,
        'can_edit' => false,
        'can_track_scans' => false,
        'has_branding' => true,
    ],

    'free' => [
        'scan_limit_per_code' => 10,
        'can_add_logo' => true, // But limited to 1 QR code (tracked in user.custom_logo_count)
        'max_custom_logos' => 1,
        'can_edit' => false, // Static only
        'can_track_scans' => false,
        'has_branding' => true,
    ],

    'premium' => [
        'scan_limit_per_code' => null, // Unlimited
        'can_add_logo' => true,
        'max_custom_logos' => null, // Unlimited
        'can_edit' => true, // Edit anytime
        'can_track_scans' => true,
        'has_branding' => false,
        'can_create_dynamic' => true, // Dynamic QR codes with /r/{slug} redirect
    ],
];
