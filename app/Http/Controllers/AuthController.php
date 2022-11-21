<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login;
use App\Http\Requests\PasswordReset;
use App\Http\Requests\PasswordResetForm;
use App\Http\Requests\Register;
use App\Http\Requests\RequestPasswordReset;
use App\Services\CustomerService;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use App\Repositories\TranslationRepository;

class AuthController extends Controller
{
    private $_customerService;
    private $_translations;

    public function __construct(CustomerService $customerService, TranslationRepository $translationRepository)
    {
        $this->_customerService = $customerService;

        $this->_translations = Cache::rememberForever('translations_web', function () use ($translationRepository) {
            return $translationRepository->default(locale())['items'];
        });
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Login $request)
    {
        $response = $this->_customerService->login($request->get('email'), $request->get('password'));

        if($response) {
            return redirect()->route(locale() . '.customer.profile');
        }

        return redirect()->route(locale() . '.login')->with('error', $this->_translations['auth.login.error']['text']);
    }

    public function logout()
    {
        $this->_customerService->logout();
        return redirect()->route(locale() . '.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Register $request)
    {
        $response = $this->_customerService->register($request->get('name'), $request->get('email'), $request->get('password'), $request->get('password_confirmation'));

        if($response) {
            return redirect()->route(locale() . '.customer.profile');
        }

        return redirect()->route(locale() . '.register')->with('error', $response['error']);
    }

    public function forgottenPasswordForm()
    {
        return view('auth.forgotten-password');
    }

    public function passwordResetForm(PasswordResetForm $request)
    {
        return view('auth.reset-password');
    }

    public function requestPasswordReset(RequestPasswordReset $request)
    {
        $response = $this->_customerService->requestPasswordReset($request->get('email'));

        if($response) {
            return redirect()->back()->with('success', $this->_translations['auth.request_password_reset.success']['text']);
        }

        return redirect()->back()->with('error', $this->_translations['auth.request_password_reset.error']['text']);
    }

    public function passwordReset(PasswordReset $request)
    {
        $response = $this->_customerService->passwordReset(
            $request->get('email'),
            $request->get('token'),
            $request->get('password'),
            $request->get('password_confirmation')
        );

        if($response) {
            return redirect()->route(locale() . '.login')->with('success', $this->_translations['auth.password_reset.success']['text']);
        }

        return redirect()->back()->with('error', $this->_translations['auth.password_reset.error']['text']);
    }
}
