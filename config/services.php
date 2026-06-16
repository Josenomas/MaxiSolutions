<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    
    'webpay' => [
        'environment' => env('WEBPAY_ENVIRONMENT', 'integration'),
        'commerce_code' => env('WEBPAY_COMMERCE_CODE'),
        'api_key' => env('WEBPAY_API_KEY'),
    ],

    'flow' => [
        'environment' => env('FLOW_ENVIRONMENT', 'sandbox'),
        'api_key' => env('FLOW_API_KEY'),
        'secret_key' => env('FLOW_SECRET_KEY'),
    ],

];
