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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'keycloak' => [
        'base_url' => env('https://keycloak:8080/auth'),
        'realm' => env('Kostiuk'),
        'client_id' => env('laravel-app'),
        'client_secret' => env('RGUGKGZ8XEhOKyWjdp8bXw2DuccINSK8'),
        'redirect' => env('http://keycloak:8080/callback'),
        'public_key' => env('-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA34cW7aPSiBLdogyHsb156XnmZEf8ru5HEhdjvu3zLJG5cqOXpaShTOMbcdUNIMEOif6G2EcVBuuoNkHzaUaOq68Rp0KoMJcDWA89NT4oVrOUO7nt8XHVWU2qVI/wuelfmc/3zDzV7ROC4lg2VFI4b8zHQpR7XS2x8Egn6m0ou62M7GoITO8NNy+vWR0I69ji5KDXtgCPlbmHTMq3FxrmynPynqiQKppZPcGiTuL+iNgylNAg+HRe4khicdf+Q/MeaLm8e1XAd6BiqIduvhOziJcC2tADEoQBARJZ9rJ+nL/K/87T1bdMFym91jHd5dtWRAdf0tCtBdtgCQp7gmsf3QIDAQAB\n-----END PUBLIC KEY-----'),
        'token_url' => env('KEYCLOAK_BASE_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/token',
        'logout_url' => env('KEYCLOAK_BASE_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/logout',
    ],

];