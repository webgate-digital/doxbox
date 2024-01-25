<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use GuzzleHttp\Client;

class ProductRepository
{
    private const LIST_URL = '/products';
    private const DETAIL_URL = '/products/detail';
    private const FILTER_PRICES_URL = '/products/filter-prices';
    private const AVAILABILITY_URL = '/products/availability';
    protected const UPSELL_URL = '/products/upsell';
    private const SEARCH_URL = '/products/search';
    private const CATEGORIES_URL = '/product-categories';
    private const BRANDS_URL = '/product-brands';
    private const CATEGORY_URL = '/product-categories/detail';
    private const ATTRIBUTES_URL = '/product-attributes';
    protected const VARIANT_AVAILABILITY_URL = '/products/variant-availability';
    protected $client;

    private function headers()
    {
        return [
            'Accept' => 'application/json',
            'Catalog' => config('frontstore.catalog'),
            'Token' => config('frontstore.api_key'),
            'Shipping-Country' => session()->get('shipping_country'),
            'Locale' => locale(),
            'Currency' => strtolower(session()->get('currency')),
            'Billing-Country' => config('frontstore.billing_country'),
        ];
    }

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    public function list(string $locale, string $currency, int $limit = 0, int $offset = 0, string $order = 'desc', string $sort = 'score', int $min_price = 0, int $max_price = PHP_INT_MAX, array $attributes = [], string $category = null, string $brand = null, array $flags = []): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint_v2') . self::LIST_URL, [
            'headers' => $this->headers(),
            'query' => [
                'limit' => $limit,
                'offset' => $offset,
                'order' => $order,
                'sort' => $sort,
                'locale' => $locale,
                'currency' => $currency,
                'min_price' => $min_price,
                'max_price' => $max_price,
                'attributes' => $attributes,
                'category' => $category,
                'brand' => $brand,
                'show_sold_out' => true,
                'flags' => $flags,
            ]
        ]);

        return $this->response($response);
    }

    public function variantsTreeV2(string $uuid): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint_v2') . '/products/variants-tree', [
            'headers' => $this->headers(),
            'query' => [
                'uuid' => $uuid
            ]
        ]);

        return $this->response($response);
    }

    public function search(string $locale, string $currency, string $keyword): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint_v3') . self::SEARCH_URL, [
            'headers' => [
                'Accept' => 'application/json',
                'Catalog' => config('frontstore.catalog'),
                'Token' => config('frontstore.api_key'),
                'Locale' => locale(),
                'Currency' => strtolower(session()->get('currency')),
                'Billing-Country' => config('frontstore.billing_country'),
            ],
            'query' => [
                'limit' => 10,
                'offset' => 0,
                'query' => $keyword,
            ]
        ]);

        return $this->response($response);
    }

    public function categories(string $locale, int $limit = 0, int $offset = 0, string $order = 'desc', string $sort = 'score'): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::CATEGORIES_URL, [
            'headers' => $this->headers(),
            'query' => [
                'limit' => $limit,
                'offset' => $offset,
                'order' => $order,
                'sort' => $sort,
                'locale' => $locale,
            ]
        ]);

        return $this->response($response);
    }

    public function attributes(string $locale, int $limit = 0, int $offset = 0, string $order = 'desc', string $sort = 'created_at'): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::ATTRIBUTES_URL, [
            'headers' => $this->headers(),
            'query' => [
                'limit' => $limit,
                'offset' => $offset,
                'order' => $order,
                'sort' => $sort,
                'locale' => $locale,
            ]
        ]);

        return $this->response($response);
    }

    public function brands(string $locale, int $limit = 0, int $offset = 0, string $order = 'asc', string $sort = 'name'): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::BRANDS_URL, [
            'headers' => $this->headers(),
            'query' => [
                'limit' => $limit,
                'offset' => $offset,
                'order' => $order,
                'sort' => $sort,
                'locale' => $locale,
            ]
        ]);
        return $this->response($response);
    }

    public function detail(string $locale, string $currency, string $slug): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint_v2') . self::DETAIL_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'slug' => $slug
            ]
        ]);

        return $this->response($response);
    }

    public function category(string $locale, string $slug): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::CATEGORY_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'slug' => $slug
            ]
        ]);

        return $this->response($response);
    }

    public function getFilterPrices(string $currency): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::FILTER_PRICES_URL, [
            'headers' => $this->headers(),
            'query' => [
                'currency' => $currency,
            ]
        ]);

        return $this->response($response);
    }

    public function availability(string $locale, string $currency, string $uuid): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::AVAILABILITY_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'uuid' => $uuid
            ]
        ]);

        return $this->response($response);
    }

    public function upsell(string $locale, string $currency, array $cart): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::UPSELL_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'cart' => $cart,
            ]
        ]);

        return $this->response($response);
    }

    protected function response($response)
    {
        if ($response->getStatusCode() === 401) {
            $errors = json_decode($response->getBody(), true);
            throw new UnauthorizedException(json_encode($errors));
        }

        if ($response->getStatusCode() === 422) {
            $errors = json_decode($response->getBody(), true)['errors'];
            throw new ValidationException(json_encode($errors));
        }

        if ($response->getStatusCode() === 404) {
            throw new NotFoundException();
        }

        return json_decode($response->getBody(), true);
    }

    public function variantAvailability(string $locale, string $currency, string $uuid): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::VARIANT_AVAILABILITY_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'uuid' => $uuid
            ]
        ]);

        return $this->response($response);
    }
}
