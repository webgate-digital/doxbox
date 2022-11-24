<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use Session;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use Throwable;

class CustomerService
{
    private $_customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->_customerRepository = $customerRepository;
    }

    public function login(string $email, string $password): bool
    {
        try {
            $response = $this->_customerRepository->login($email, $password);

            Session::put('access_token', $response['items']['access_token']);
            Session::put('access_token_expiration', ($response['timestamp'] + $response['items']['expires_in']));

            return true;
        } catch (UnauthorizedException | ValidationException $e) {
            return false;
        }
    }

    public function logout(): void
    {
        try {
            $this->_customerRepository->logout();
        } catch (UnauthorizedException $e) {
        }

        Session::forget(['access_token', 'access_token_expiration', 'me', 'cart']);
    }

    public function me(): array
    {
        try {
            return $this->_customerRepository->me()['item'];
        } catch (UnauthorizedException $e) {
            return [];
        }
    }

    public function register(string $name, string $email, string $password, string $password_confirmation)
    {
        try {
            $response = $this->_customerRepository->register($name, $email, $password, $password_confirmation);

            Session::put('access_token', $response['items']['access_token']);
            Session::put('access_token_expiration', ($response['timestamp'] + $response['items']['expires_in']));

            return true;
        } catch (UnauthorizedException | ValidationException $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function applyForB2B(array $data)
    {
        try {
            $this->_customerRepository->applyForB2B($data);

            return true;
        } catch (Throwable $throwable) {
            return false;
        }
    }

    public function requestPasswordReset(string $email)
    {
        try {
            $this->_customerRepository->requestPasswordReset($email);

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function passwordReset(string $email, string $token, string $password, string $passwordConfirmation)
    {
        try {
            $this->_customerRepository->passwordReset($email, $token, $password, $passwordConfirmation);

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
