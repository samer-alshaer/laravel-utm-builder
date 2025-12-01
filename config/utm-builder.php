<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The default base URL for generating UTM links. Falls back to APP_URL
    | if not specified.
    |
    */
    'base_url' => env('UTM_BASE_URL', env('APP_URL', 'http://localhost')),

    /*
    |--------------------------------------------------------------------------
    | Client Website URL
    |--------------------------------------------------------------------------
    |
    | If you're generating links for a different domain (e.g., client-facing
    | website or marketing site), set this URL here.
    |
    */
    'client_url' => env('UTM_CLIENT_URL', env('APP_URL', 'http://localhost')),

    /*
    |--------------------------------------------------------------------------
    | Reference Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix used when adding reference tracking parameters using
    | the ref() method. Default: 'ref_'
    |
    */
    'ref_prefix' => 'ref_',

    /*
    |--------------------------------------------------------------------------
    | Sanitization Options
    |--------------------------------------------------------------------------
    |
    | These options control how UTM parameter values are sanitized.
    |
    */
    'lowercase' => true,
    'replace_spaces' => true,
    'space_replacement' => '_',

    /*
    |--------------------------------------------------------------------------
    | UTM Presets
    |--------------------------------------------------------------------------
    |
    | Define reusable UTM parameter combinations for different scenarios.
    | Use these presets with: Utm::make()->preset('preset_name')
    |
    | Available UTM parameters:
    | - utm_source: The referrer (e.g., google, newsletter, facebook)
    | - utm_medium: Marketing medium (e.g., cpc, email, social)
    | - utm_campaign: Product, promo code, or slogan
    | - utm_term: Identify paid search keywords
    | - utm_content: Differentiate similar content/links
    | - utm_id: Campaign ID for tracking
    |
    */
    'presets' => [
        /*
        |----------------------------------------------------------------------
        | Email Campaigns
        |----------------------------------------------------------------------
        */
        'email' => [
            'utm_source' => 'email',
            'utm_medium' => 'email',
        ],

        'newsletter' => [
            'utm_source' => 'newsletter',
            'utm_medium' => 'email',
            'utm_campaign' => 'newsletter',
        ],

        'transactional' => [
            'utm_source' => 'system',
            'utm_medium' => 'email',
            'utm_campaign' => 'transactional',
        ],

        /*
        |----------------------------------------------------------------------
        | SMS Campaigns
        |----------------------------------------------------------------------
        */
        'sms' => [
            'utm_source' => 'sms',
            'utm_medium' => 'sms',
        ],

        'sms_notification' => [
            'utm_source' => 'system',
            'utm_medium' => 'sms',
            'utm_campaign' => 'notification',
        ],

        'sms_promotion' => [
            'utm_source' => 'marketing',
            'utm_medium' => 'sms',
            'utm_campaign' => 'promotion',
        ],

        /*
        |----------------------------------------------------------------------
        | Social Media
        |----------------------------------------------------------------------
        */
        'facebook' => [
            'utm_source' => 'facebook',
            'utm_medium' => 'social',
        ],

        'twitter' => [
            'utm_source' => 'twitter',
            'utm_medium' => 'social',
        ],

        'linkedin' => [
            'utm_source' => 'linkedin',
            'utm_medium' => 'social',
        ],

        'instagram' => [
            'utm_source' => 'instagram',
            'utm_medium' => 'social',
        ],

        'whatsapp' => [
            'utm_source' => 'whatsapp',
            'utm_medium' => 'social',
            'utm_campaign' => 'share',
        ],

        /*
        |----------------------------------------------------------------------
        | Paid Advertising
        |----------------------------------------------------------------------
        */
        'google_ads' => [
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
        ],

        'facebook_ads' => [
            'utm_source' => 'facebook',
            'utm_medium' => 'paid_social',
        ],

        /*
        |----------------------------------------------------------------------
        | Referral & Affiliate
        |----------------------------------------------------------------------
        */
        'referral' => [
            'utm_source' => 'referral',
            'utm_medium' => 'referral',
        ],

        'affiliate' => [
            'utm_source' => 'affiliate',
            'utm_medium' => 'affiliate',
        ],

        /*
        |----------------------------------------------------------------------
        | Internal Links
        |----------------------------------------------------------------------
        */
        'internal' => [
            'utm_source' => 'internal',
            'utm_medium' => 'link',
        ],

        'admin_panel' => [
            'utm_source' => 'admin_panel',
            'utm_medium' => 'internal',
        ],

        /*
        |----------------------------------------------------------------------
        | Add your custom presets below
        |----------------------------------------------------------------------
        */

        // 'booking_payment' => [
        //     'utm_source' => 'system',
        //     'utm_medium' => 'email',
        //     'utm_campaign' => 'booking_payment',
        // ],
    ],
];
