<?php

return [
    'catalog' => env('CATALOG_ID'),
    'api_key' => env('API_KEY'),
    'billing_country' => env('BILLING_COUNTRY', 'SK'),
    'api_endpoint' => env('API_ENDPOINT'),
    'api_endpoint_v2' => env('API_ENDPOINT_V2'),
    'api_endpoint_v3' => env('API_ENDPOINT_V3'),
    'cacheResetToken' => env('CACHE_RESET_TOKEN'),
    'defaults' => [
        'limit' => [
            'products' => 18,
        ]
    ]
];
