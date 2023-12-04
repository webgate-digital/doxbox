<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use GuzzleHttp\Client;

class SettingRepository
{
    private const CATALOG_URL = '/settings/catalog';
    private const SUPPLIER_URL = '/settings/supplier';
    private const PACKETA_URL = '/settings/packeta';
    private const DOCUMENTS_URL = '/settings/documents';
    private const GTM_URL = '/settings/gtm';
    private $client;

    private function headers()
    {
        return [
            'Accept' => 'application/json',
            'Catalog' => config('frontstore.catalog'),
            'Token' => config('frontstore.api_key'),
            'Shipping-Country' => session()->get('shipping_country'),
            'Locale' => locale(),
            'Currency' => session()->get('currency'),
        ];
    }

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    public function catalog(string $locale): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::CATALOG_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale
            ],
        ]);

        return $this->response($response);
    }

    public function documents(string $locale): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::DOCUMENTS_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale
            ],
        ]);

        return $this->response($response);
    }

    public function supplier(string $locale): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::SUPPLIER_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale
            ],
        ]);

        return $this->response($response);
    }

    public function gtm(): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::GTM_URL, [
            'headers' => $this->headers()
        ]);

        return $this->response($response);
    }

    public function packeta(): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::PACKETA_URL, [
            'headers' => $this->headers()
        ]);

        return $this->response($response);
    }

    private function response($response)
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
}
