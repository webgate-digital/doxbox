<?php

namespace App\Http\Middleware;

use App\Repositories\SetupRepository;
use Closure;
use Illuminate\Support\Facades\Cache;

class Currency
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
        $setup = Cache::rememberForever(locale() . '_setup', function () {
            return $this->_setupRepository->list()['items'];
        });
        $request->session()->put('currency', $setup['catalog']['currencies'][0]);

        return $next($request);
    }
}
