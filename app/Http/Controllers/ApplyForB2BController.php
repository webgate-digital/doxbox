<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyForB2BStore;
use App\Repositories\TranslationRepository;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Throwable;

class ApplyForB2BController extends Controller
{
    private $_translations;

    public function __construct(private CustomerService $_customerService, TranslationRepository $translationRepository)
    {
        $this->_translations = Cache::rememberForever('translations_web', function () use ($translationRepository) {
            return $translationRepository->default(locale())['items'];
        });
    }

    public function index()
    {
        abort_if(is_b2b(), 403);

        $me = Session::get('me');

        return view('b2b.index', compact('me'));
    }

    public function store(ApplyForB2BStore $request)
    {
        $response = $this->_customerService->applyForB2B($request->validated());

        if (! $response) {
            return back()->withErrors($this->_translations['general.error_your_request_has_been_not_sent']['text']);
        }

        return back()->with('success', $this->_translations['general.your_request_has_been_sent']['text']);
    }
}
