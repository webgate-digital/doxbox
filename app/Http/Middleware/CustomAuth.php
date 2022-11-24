<?php

namespace App\Http\Middleware;

use App\Services\CustomerService;
use Closure;
use Illuminate\Http\Request;
use Session;
use App\Exceptions\UnauthorizedException;

class CustomAuth
{
    private $_customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->_customerService = $customerService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('access_token') || !Session::has('access_token_expiration')) {
            return $this->logout();
        }

        if ((Session::get('access_token_expiration') - time()) < 60) {
            return $this->logout();
        }

        $me = $this->_customerService->me();

        if (!count($me)) {
            return $this->logout();
        }

        Session::put('me', $me);

        return $next($request);
    }

    private function logout()
    {
        Session::forget(['access_token', 'access_token_expiration', 'me']);
        return redirect()->route(locale() . '.login');
    }
}