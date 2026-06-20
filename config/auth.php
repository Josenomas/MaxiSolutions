<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'paes' => [
            'driver' => 'session',
            'provider' => 'paes_users',
        ],

        'chatbot' => [
            'driver' => 'session',
            'provider' => 'chatbot_users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'paes_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\PaesUser::class,
        ],

        'chatbot_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\Chatbot\ChatbotUser::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'paes_users' => [
            'provider' => 'paes_users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'chatbot_users' => [
            'provider' => 'chatbot_users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
