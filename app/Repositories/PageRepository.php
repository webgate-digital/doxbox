<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use GuzzleHttp\Client;

class PageRepository
{
    private const LIST_URL = '/pages';
    private const LIST_DETAIL = '/pages/detail';

    private function headers()
    {
        return [
            'Accept' => 'application/json',
            'Catalog' => config('frontstore.catalog'),
            'Token' => config('frontstore.api_key'),
            'Shipping-Country' => session()->get('shipping_country'),
            'Locale' => locale(),
            'Currency' => strtolower(session()->get('currency')),
        ];
    }

    public function list(string $locale, string $currency, int $limit = 0, int $offset = 0, string $order = 'desc', string $sort = 'score', bool $header = false, bool $footer = false, bool $toc = false, bool $gdpr = false): array
    {
        $client = new Client(['http_errors' => false]);

        $response = $client->get(config('frontstore.api_endpoint') . self::LIST_URL, [
            'headers' => $this->headers(),
            'query' => [
                'limit' => $limit,
                'offset' => $offset,
                'order' => $order,
                'sort' => $sort,
                'locale' => $locale,
                'currency' => $currency,
                'list_only_header' => $header,
                'list_only_footer' => $footer,
                'list_only_toc' => $toc,
                'list_only_gdpr' => $gdpr
            ]
        ]);

        if ($response->getStatusCode() === 401) {
            $errors = json_decode($response->getBody(), true);
            throw new UnauthorizedException(json_encode($errors));
        }

        if ($response->getStatusCode() === 422) {
            $errors = json_decode($response->getBody(), true)['errors'];
            throw new ValidationException(json_encode($errors));
        }

        return json_decode($response->getBody(), true);
    }

    public function detail(string $locale, string $currency, string $slug): array
    {
        $client = new Client(['http_errors' => false]);

        $response = $client->get(config('frontstore.api_endpoint') . self::LIST_DETAIL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'slug' => $slug
            ]
        ]);

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
