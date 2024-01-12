<?php

return [
    'catalog' => env('CATALOG_ID'),
    'api_key' => env('API_KEY'),
    'api_endpoint' => env('API_ENDPOINT'),
    'api_endpoint_v2' => env('API_ENDPOINT_V2'),
    'cacheResetToken' => env('CACHE_RESET_TOKEN'),
    'defaults' => [
        'limit' => [
            'products' => 18,
        ]
    ]
];
