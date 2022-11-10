<?php

namespace App\Repositories;


use GuzzleHttp\Client;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;

class BlogArticleRepository
{
    protected const LIST_URL = '/blog';
    protected const DETAIL_URL = '/blog/detail';
    protected const CATEGORY_URL = '/blog/category';
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    private function headers()
    {
        return [
            'Accept' => 'application/json',
            'Catalog' => config('frontstore.catalog') . str_replace("CS", "CZ", strtoupper(locale())),
            'Token' => config('frontstore.api_key')[locale()]
        ];
    }

    public function list(string $locale, int $limit = 0, int $offset = 0, string $order = 'desc', string $sort = 'score'): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::LIST_URL, [
            'headers' => $this->headers(),
            'query' => [
                'currency' => session()->get('currency'),
                'limit' => $limit,
                'offset' => $offset,
                'order' => $order,
                'sort' => $sort,
                'locale' => $locale,
            ]
        ]);

        return $this->response($response);
    }

    public function category(string $locale, string $currency, string $slug): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::CATEGORY_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'slug' => $slug
            ]
        ]);

        return $this->response($response);
    }

    public function detail(string $locale, string $currency, string $slug): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::DETAIL_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'slug' => $slug
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
}
