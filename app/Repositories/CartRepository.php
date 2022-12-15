<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use GuzzleHttp\Client;

class CartRepository
{
    protected const LIST_URL = '/cart';
    private const CHECKOUT_URL = '/checkout';
    private const VALIDATE_CARDPAY = '/validate-cardpay';
    protected $client;

    private function headers()
    {
        return [
            'Accept' => 'application/json',
            'Catalog' => config('frontstore.catalog'),
            'Token' => config('frontstore.api_key')
        ];
    }

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    public function list(string $locale, string $currency, array $cart, array $multipack, string $voucher = null, string $shippingCountry = null, string $shipping = null, string $payment = null, array $checkoutSupport = [], array $variants = []): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::LIST_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'cart' => $cart,
                'multipack' => $multipack,
                'voucher' => $voucher,
                'shipping_country' => $shippingCountry,
                'shipping_type' => $shipping,
                'payment_type' => $payment,
                'variants' => $variants,
                'checkout_support' => $checkoutSupport
            ]
        ]);

        return $this->response($response);
    }

    public function checkout(array $data): array
    {
        $query = [
            'currency' => $data['currency'],
            'locale' => $data['locale'],
            'shipping_country' => $data['shipping_country'],
            'shipping_type' => $data['shipping_type'],
            'meta' => $data['meta'],
            'payment_type' => $data['payment_type'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'street' => $data['street'],
            'house_number' => $data['house_number'],
            'city' => $data['city'],
            'zip' => $data['zip'],
            'state' => $data['state'],
            'country' => $data['country'],
            'delivery_name' => $data['shipping_name'],
            'delivery_street' => $data['shipping_street'],
            'delivery_house_number' => $data['shipping_house_number'],
            'delivery_city' => $data['shipping_city'],
            'delivery_zip' => $data['shipping_zip'],
            'delivery_state' => $data['shipping_state'],
            'delivery_country' => isset($data['shipping_name']) && $data['shipping_name'] ? $data['shipping_country'] : null,
            'company_name' => $data['company_name'],
            'company_id' => $data['company_id'],
            'company_tax_id' => $data['company_tax_id'],
            'company_vat_id' => $data['company_vat_id'],
            'company_address' => $data['company_address'],
            'company_city' => $data['company_city'],
            'company_zip' => $data['company_zip'],
            'company_state' => $data['company_state'],
            'company_country' => $data['company_country'],
            'notes' => $data['notes'],
            'toc' => $data['toc'],
            'newsletter' => $data['newsletter'],
            'heureka_allowed' => $data['heureka_allowed'],
            'success_url' => $data['success_url'],
            'error_url' => $data['error_url'],
            'cart' => $data['cart'],
        ];

        $response = $this->client->post(config('frontstore.api_endpoint') . self::CHECKOUT_URL, [
            'headers' => $this->headers(),
            'form_params' => $query
        ]);

        return $this->response($response);
    }

    public function validateCardpay(string $currency, array $data): array
    {
        $data['currency'] = $currency;

        $response = $this->client->get(config('frontstore.api_endpoint') . self::VALIDATE_CARDPAY, [
            'headers' => $this->headers(),
            'query' => $data
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
