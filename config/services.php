<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Bangladesh Payment Gateways & Mobile Financial Services
    |--------------------------------------------------------------------------
    */

    'bkash' => [
        'app_key' => env('BKASH_APP_KEY', ''),
        'app_secret' => env('BKASH_APP_SECRET', ''),
        'username' => env('BKASH_USERNAME', ''),
        'password' => env('BKASH_PASSWORD', ''),
        'sandbox' => env('BKASH_SANDBOX', true),
        'base_url' => env('BKASH_SANDBOX', true) 
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta' 
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta',
    ],

    'sslcommerz' => [
        'store_id' => env('SSLCZ_STORE_ID', ''),
        'store_password' => env('SSLCZ_STORE_PASSWORD', ''),
        'sandbox' => env('SSLCZ_SANDBOX', true),
        'api_url' => env('SSLCZ_SANDBOX', true) 
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php' 
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php',
        'validate_url' => env('SSLCZ_SANDBOX', true)
            ? 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
            : 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php',
    ],

    'nagad' => [
        'merchant_id' => env('NAGAD_MERCHANT_ID', ''),
        'public_key' => env('NAGAD_PUBLIC_KEY', ''),
        'private_key' => env('NAGAD_PRIVATE_KEY', ''),
        'sandbox' => env('NAGAD_SANDBOX', true),
    ],

    'cod' => [
        'require_advance_delivery_fee' => env('COD_REQUIRE_ADVANCE_FEE', true),
        'dhaka_delivery_fee' => (float) env('COD_DHAKA_FEE', 80.00),
        'outside_dhaka_delivery_fee' => (float) env('COD_OUTSIDE_DHAKA_FEE', 150.00),
    ],

];
