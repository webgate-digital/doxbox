<?php

namespace App\Http\Middleware;

use App\Repositories\SetupRepository;
use Cache;
use Closure;

class ShippingCountry
{
    private $_setupRepository;

    public function __construct(SetupRepository $setupRepository)
    {
        $this->_setupRepository = $setupRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $shippingCountryInSession = session()->get('shipping_country', null);

        if (!$shippingCountryInSession) {

            $items = Cache::rememberForever('shipping_countries', function () {
                return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
            });

            session()->put('shipping_country', key($items));
        }

        return $next($request);
    }
}
