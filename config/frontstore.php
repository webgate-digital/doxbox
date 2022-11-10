<?php

return [
    'catalog' => env('CATALOG_ID'),
    'api_key' => [
        'sk' => env('API_KEY_SK'),
        'cs' => env('API_KEY_CS')
    ],
    'api_endpoint' => env('API_ENDPOINT'),
    'cacheResetToken' => env('CACHE_RESET_TOKEN'),
    'defaults' => [
        'limit' => [
            'products' => 18,
        ]
    ]
];
