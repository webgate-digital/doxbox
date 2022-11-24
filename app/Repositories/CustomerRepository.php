<?php

namespace App\Repositories;

use App\Exceptions\InternalServerErrorException;
use GuzzleHttp\Client;
use PhpParser\Node\Stmt\If_;
use Session;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;

class CustomerRepository
{
    private const LOGIN_URL = '/auth/login';
    private const REGISTER_URL = '/auth/register';
    private const APPLY_FOR_B2B = '/auth/apply-for-b2b';
    private const ME_URL = '/auth/me';
    private const LOGOUT_URL = '/auth/logout';
    private const PRODUCTS_URL = '/products';
    private const DETAIL_URL = '/products/detail';
    private const REQUEST_PASSWORD_RESET = '/auth/request-password-reset';
    private const PASSWORD_RESET = '/auth/password-reset';
    private $client;
    
    private function headers()
    {
        return [
            'Accept' => 'application/json',
            'Catalog' => config('frontstore.catalog') . str_replace("CS", "CZ", strtoupper(locale())),
            'Token' => config('frontstore.api_key')[locale()]
        ];
    }

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    public function login(string $email, string $password)
    {
        $response = $this->client->post(config('frontstore.api_endpoint') . self::LOGIN_URL, [
            'headers' => $this->headers(),
            'form_params' => [
                'email' => $email,
                'password' => $password
            ]
        ]);

        return $this->response($response);
    }

    public function register(string $name, string $email, string $password, string $password_confirmation)
    {
        $response = $this->client->post(config('frontstore.api_endpoint') . self::REGISTER_URL, [
            'headers' => $this->headers(),
            'form_params' => [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password_confirmation,
                'locale' => locale(),
                'currency' => session()->get('currency')
            ]
        ]);

        return $this->response($response);
    }

    public function me()
    {
        $headers = $this->headers();
        $headers['Authorization'] = 'Bearer ' . Session::get('access_token');

        $response = $this->client->get(config('frontstore.api_endpoint') . self::ME_URL, [
            'headers' => $headers
        ]);

        return $this->response($response);
    }

    public function logout()
    {
        $headers = $this->headers();
        $headers['Authorization'] = 'Bearer ' . Session::get('access_token');

        $response = $this->client->get(config('frontstore.api_endpoint') . self::LOGOUT_URL, [
            'headers' => $headers
        ]);

        return $this->response($response);
    }

    public function applyForB2B(array $data)
    {
        $headers = $this->headers();
        $headers['Authorization'] = 'Bearer ' . Session::get('access_token');

        $response = $this->client->post(config('frontstore.api_endpoint') . self::APPLY_FOR_B2B, [
            'headers' => $headers,
            'form_params' => $data
        ]);

        return $this->response($response);
    }

    public function requestPasswordReset(string $email)
    {
        $response = $this->client->post(config('frontstore.api_endpoint') . self::REQUEST_PASSWORD_RESET, [
            'headers' => $this->headers(),
            'form_params' => [
                'email' => $email,
                'reset_url' => route(locale() . '.password.reset.form')
            ]
        ]);

        return $this->response($response);
    }

    public function passwordReset(string $email, string $token, string $password, string $passwordConfirmation)
    {
        $response = $this->client->post(config('frontstore.api_endpoint') . self::PASSWORD_RESET, [
            'headers' => $this->headers(),
            'form_params' => [
                'email' => $email,
                'token' => $token,
                'password' => $password,
                'password_confirmation' => $passwordConfirmation,
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

        if ($response->getStatusCode() === 500) {
            throw new InternalServerErrorException();
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
