<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Repositories\PageRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SettingRepository;
use Cache;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private $_productRepository;
    private $_pageRepository;

    public function __construct(ProductRepository $productRepository, PageRepository $pageRepository)
    {
        $this->_productRepository = $productRepository;
        $this->_pageRepository = $pageRepository;
    }

    public function homepage()
    {
        $products = Cache::rememberForever('homepage_products_list', function () {
            return $this->_productRepository->list(locale(), session()->get('currency'), 8)['items'];
        });

        $categories = Cache::rememberForever('homepage_categories_list', function () {
            return $this->_productRepository->categories(locale(), 8)['items'];
        });

        return view('pages.homepage', compact('products', 'categories'));
    }

    public function page(string $slug)
    {
        $item = Cache::rememberForever('page_' . $slug, function () use ($slug) {
            try {
                return $this->_pageRepository->detail(locale(), session()->get('currency'), $slug)['item'];
            } catch (NotFoundException | ValidationException $e) {
                abort(404);
            }
        });

        return view('pages.page', compact('item'));
    }

    public function blog()
    {
        $supplier = Cache::rememberForever('blog_articles', function () {
            $_settingRepository = new SettingRepository();
            return $_settingRepository->supplier()['items'];
        });

        return view('pages.blog', compact('supplier'));
    }

    public function contact()
    {
        $supplier = Cache::rememberForever('supplier_settings', function () {
            $_settingRepository = new SettingRepository();
            return $_settingRepository->supplier()['items'];
        });

        return view('pages.contact', compact('supplier'));
    }

    public function thankYou(Request $request)
    {
        $order = session()->get('order', null);

        if (!$order) {
            abort(404);
        }

        session()->forget('order');
        $params = $request->all();

        return view('pages.thank_you', compact('order', 'params'));
    }
}
