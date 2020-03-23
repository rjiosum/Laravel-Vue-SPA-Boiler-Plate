<?php

return [
    'url' => env('PASSPORT_OAUTH_TOKEN_URL'),

    'cookie' => [
        'name' => env('PASSPORT_COOKIE_NAME'),
        'minutes' => env('PASSPORT_COOKIE_MINUTES'),
        'path' => env('PASSPORT_COOKIE_PATH'),
        'domain' => env('PASSPORT_COOKIE_DOMAIN'),
        'secure' => env('PASSPORT_COOKIE_SECURE'), //null for localhost, true for production
        'httponly' => env('PASSPORT_COOKIE_HTTPONLY'),
        'raw' => env('PASSPORT_COOKIE_RAW'),
        'samesite' => env('PASSPORT_COOKIE_SAMESITE') //strict or lxs
    ]

];