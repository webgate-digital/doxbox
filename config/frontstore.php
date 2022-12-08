<?php

return [
    'catalog' => env('CATALOG_ID'),
    'api_key' => env('API_KEY'),
    'api_endpoint' => env('API_ENDPOINT'),
    'cacheResetToken' => env('CACHE_RESET_TOKEN'),
    'defaults' => [
        'limit' => [
            'products' => 18,
        ]
    ]
];
