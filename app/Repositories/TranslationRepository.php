<?php

namespace App\Repositories;

use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use GuzzleHttp\Client;

class TranslationRepository
{
    private const DEFAULT_URL = '/language-lines';
    private const VALIDATION_URL = '/language-lines/validation';

    private function headers()
    {
        return [
            'Accept' => 'application/json',
            'Catalog' => config('frontstore.catalog'),
            'Token' => config('frontstore.api_key'),
            'Shipping-Country' => session()->get('shipping_country'),
        ];
    }

    public function validation(string $locale): array
    {
        $client = new Client(['http_errors' => false]);

        $response = $client->get(config('frontstore.api_endpoint') . self::VALIDATION_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'type' => 'default'
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

    public function default(string $locale): array
    {
        $client = new Client(['http_errors' => false]);

        $response = $client->get(config('frontstore.api_endpoint') . self::DEFAULT_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'type' => 'default'
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
}
