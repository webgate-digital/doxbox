<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class OrderRepository
{
    private const ORDERS = '/auth/orders';
    private const ORDER_SHOW = '/auth/order';

    private $client;
    
    private function headers()
    {
        return [
            'Accept' => 'application/json',
            'Catalog' => config('frontstore.catalog'),
            'Token' => config('frontstore.api_key'),
            'Shipping-Country' => session()->get('shipping_country'),
        ];
    }

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    public function getOrders()
    {
        $headers = $this->headers();
        $headers['Authorization'] = 'Bearer ' . Session::get('access_token');

        $response = $this->client->get(config('frontstore.api_endpoint') . self::ORDERS, [
            'headers' => $headers,
            'query' => [
                'locale' => locale(),
                'currency' => session()->get('currency')
            ]
        ]);

        return $this->response($response);
    }

    public function getOrder($uuid, $token)
    {
        $headers = $this->headers();
        $headers['Authorization'] = 'Bearer ' . Session::get('access_token');

        $response = $this->client->get(config('frontstore.api_endpoint') . self::ORDER_SHOW . '/' . $uuid, [
            'headers' => $headers,
            'query' => [
                'locale' => locale(),
                'currency' => session()->get('currency'),
                'token' => $token
            ]
        ]);

        return $this->response($response);
    }

    private function response($response)
    {
        if ($response->getStatusCode() === 401) {
            throw new UnauthorizedException();
        }

        if ($response->getStatusCode() === 422) {
            $errors = $response->getBody()->getContents();
            throw new ValidationException($errors);
        }

        if ($response->getStatusCode() === 404) {
            throw new NotFoundException();
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
