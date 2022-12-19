<?php

namespace App\Http\Middleware;

use Closure;

class EmptyCart
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
        if(count(session()->get('cart', [])) !== 0 || count(session()->get('variants', [])) !== 0) {
            return $next($request);
        }

        return redirect()->route(locale() . '.cart');
    }
}
