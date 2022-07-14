<?php

namespace App\Http\Middleware;

use Closure;

class EmptyShipping
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(session()->has('shipping_type') && session()->has('payment_type') && session()->has('toc_accepted')) {
            return $next($request);
        }

        return redirect()->route(locale() . '.cart.shipping');
    }
}
