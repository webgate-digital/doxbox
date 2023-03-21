<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use GuzzleHttp\Client;

class SetupRepository
{
    private const LIST_URL = '/setup';
    private const SHIPPING_COUNTRIES_URL = '/shipping-countries';
    private const SHIPPING_TYPES_URL = '/shipping-types';
    private const PAYMENT_TYPES_URL = '/payment-types';
    private const PAYMENTS = '/payments';
    private const VOUCHER_AVAILABILITY = '/voucher-availability';
    private const FORMAT_PRICE = '/format-price';
    private const CONTACT_SUBMIT = '/contact';
    private const FACEBOOK_XML = '/xml/facebook';
    private const GOOGLE_XML = '/xml/google';
    private const HEUREKA_XML = '/xml/heureka';
    private const MALL_XML = '/xml/mall';
    private const MALL_AVAILABILITY_XML = '/xml/mall-availability';
    private const SITEMAP_URL = '/sitemap';
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

    public function list(): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::LIST_URL, [
            'headers' => $this->headers()
        ]);

        return $this->response($response);
    }

    public function voucherAvailability(string $voucher, string $currency): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::VOUCHER_AVAILABILITY, [
            'headers' => $this->headers(),
            'query' => [
                'voucher' => $voucher,
                'currency' => $currency
            ]
        ]);

        return $this->response($response);
    }

    public function contactSubmit(string $fullname, string $email, string $message): array
    {
        $response = $this->client->post(config('frontstore.api_endpoint') . self::CONTACT_SUBMIT, [
            'headers' => $this->headers(),
            'query' => [
                'fullname' => $fullname,
                'email' => $email,
                'message' => $message
            ]
        ]);

        return $this->response($response);
    }

    public function formatPrice(float $price, string $currency): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::FORMAT_PRICE, [
            'headers' => $this->headers(),
            'query' => [
                'price' => $price,
                'currency' => $currency
            ]
        ]);

        return $this->response($response);
    }

    public function getShippingCountries(string $locale, string $currency): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::SHIPPING_COUNTRIES_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency
            ]
        ]);

        return $this->response($response);
    }

    public function getShippingTypes(string $locale, string $currency, string $country): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::SHIPPING_TYPES_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'country' => $country
            ]
        ]);

        return $this->response($response);
    }

    public function getPayments(string $locale, string $currency, string $country): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::PAYMENTS, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'country' => $country
            ]
        ]);

        return $this->response($response);
    }

    public function facebookXML(string $locale, string $currency, string $productUrl)
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::FACEBOOK_XML, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'product_url' => $productUrl,
            ]
        ]);

        return $this->response($response);
    }

    public function googleXML(string $locale, string $currency, string $productUrl)
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::GOOGLE_XML, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'product_url' => $productUrl,
            ]
        ]);

        $xml = $this->response($response);

        $content = $xml['content'];
        $header = $xml['header'];

        return response()->view('google', compact('header', 'content'))->header('Content-Type', 'text/xml');
    }

    public function heurekaXML(string $locale, string $currency, string $productUrl)
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::HEUREKA_XML, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'product_url' => $productUrl,
            ]
        ]);

        $xml = $this->response($response);

        $content = $xml['content'];
        $header = $xml['header'];

        return response()->view('heureka', compact('header', 'content'))->header('Content-Type', 'text/xml');
    }

    public function mallXML(string $locale, string $currency)
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::MALL_XML, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency
            ]
        ]);

        $xml = $this->response($response);

        $content = $xml['content'];
        $header = $xml['header'];

        return response()->view('mall', compact('header', 'content'))->header('Content-Type', 'text/xml');
    }

    public function mallAvailabilityXML(string $locale, string $currency)
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::MALL_AVAILABILITY_XML, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency
            ]
        ]);

        $xml = $this->response($response);

        $content = $xml['content'];
        $header = $xml['header'];

        return response()->view('mall_availability', compact('header', 'content'))->header('Content-Type', 'text/xml');
    }

    public function sitemap(string $locale, string $homepage_url, string $blog_url, string $products_url, string $contact_url, string $product_url, string $category_url, string $article_url, string $article_category_url)
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::SITEMAP_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'homepage_url' => $homepage_url,
                'blog_url' => $blog_url,
                'products_url' => $products_url,
                'contact_url' => $contact_url,
                'product_url' => $product_url,
                'category_url' => $category_url,
                'article_url' => $article_url,
                'article_category_url' => $article_category_url
            ]
        ]);

        $xml = $this->response($response);

        $content = $xml['content'];
        $header = $xml['header'];

        return response()->view('sitemap', compact('header', 'content'))->header('Content-Type', 'text/xml');
    }

    public function getPaymentTypes(string $locale, string $currency, string $country, string $shipping): array
    {
        $response = $this->client->get(config('frontstore.api_endpoint') . self::PAYMENT_TYPES_URL, [
            'headers' => $this->headers(),
            'query' => [
                'locale' => $locale,
                'currency' => $currency,
                'country' => $country,
                'shipping' => $shipping
            ]
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
