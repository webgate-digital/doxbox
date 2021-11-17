<?php

namespace App\Http\Controllers;

use Cache;
use Illuminate\Http\Request;
use Theshop\Frontstore\Base\Exceptions\NotFoundException;
use Theshop\Frontstore\Base\Exceptions\ValidationException;
use Theshop\Frontstore\Base\Repositories\SettingRepository;
use Theshop\Frontstore\Storyblok\Client;
use Theshop\Frontstore\Storyblok\Services\StoryblokService;
use Theshop\Frontstore\Cart\Repositories\CartRepository;
use Theshop\Frontstore\Pages\Repositories\PageRepository;
use Theshop\Frontstore\Products\Repositories\ProductRepository;

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
        $pageContent = StoryblokService::getContent('home', locale());

        $products = Cache::rememberForever('homepage_products_list', function () {
            return $this->_productRepository->list(locale(), session()->get('currency'), 8)['items'];
        });

        $categories = Cache::rememberForever('homepage_categories_list', function () {
            return $this->_productRepository->categories(locale(), 8)['items'];
        });

        return view('pages.homepage', compact('pageContent', 'products', 'categories'));
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
