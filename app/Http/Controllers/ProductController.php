<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\Filter;
use App\Repositories\ProductRepository;
use App\Repositories\SetupRepository;
use Cache;
use Illuminate\Http\Request;
use Str;

class ProductController extends Controller
{
    private $_productRepository;
    private $_setupRepository;

    public function __construct(ProductRepository $productRepository, SetupRepository $setupRepository)
    {
        $this->_productRepository = $productRepository;
        $this->_setupRepository = $setupRepository;
    }

    public function list(Filter $request)
    {
        $categories = Cache::rememberForever('product_categories', function () {
            return $this->_productRepository->categories(locale())['items'];
        });

        $category = null;

        $filterPrices = Cache::rememberForever('filter_prices', function () {
            return $this->_productRepository->getFilterPrices(session()->get('currency'))['items'];
        });

        $products = Cache::rememberForever('products_list_' . base64_encode($request->getRequestUri()), function () use ($request, $filterPrices) {
            $setup = Cache::rememberForever('setup', function () {
                return $this->_setupRepository->list()['items'];
            });
            $sortAndOrder = $request->get('sort', $setup['api']['defaults']['sort']['products']);
            $sort = implode('_', explode('_', $sortAndOrder, -1));
            $order = Str::replaceFirst($sort . '_', '', $sortAndOrder);
            $min_price = $request->get('min_price', $filterPrices['min_price']);
            $max_price = $request->get('max_price', $filterPrices['max_price']);
            $attributes = $request->get('attributes', []);
            return $this->_productRepository->list(locale(), session()->get('currency'), 0, 0, $order, $sort, $min_price, $max_price, $attributes)['items'];
        });

        $attributes = Cache::rememberForever('product_attributes', function () {
            return $this->_productRepository->attributes(locale())['items'];
        });

        return view('products.list', compact('products', 'categories', 'category', 'filterPrices', 'attributes'));
    }

    public function category(Filter $request, string $categorySlug)
    {
        try {
            $category = $this->_productRepository->category(locale(), $categorySlug)['item'];
        } catch (NotFoundException $e) {
            abort(404);
        }

        $categories = Cache::rememberForever('product_categories', function () {
            return $this->_productRepository->categories(locale())['items'];
        });

        $filterPrices = Cache::rememberForever('filter_prices', function () {
            return $this->_productRepository->getFilterPrices(session()->get('currency'))['items'];
        });

        $attributes = Cache::rememberForever('product_attributes', function () {
            return $this->_productRepository->attributes(locale())['items'];
        });

        $isAjax = $request->ajax();
        $limit = config('frontstore.defaults.limit.products');
        $page = $isAjax ? $request->get('page', 1) : 0;
        $offset = $isAjax ? ($page - 1) * $limit : 0;
        $productList = Cache::rememberForever('products_list_' . base64_encode($request->getRequestUri()), function () use ($request, $filterPrices, $category, $limit, $offset) {
            $setup = Cache::rememberForever('setup', function () {
                return $this->_setupRepository->list()['items'];
            });
            $sortAndOrder = $request->get('sort', $setup['api']['defaults']['sort']['products']);
            $sort = implode('_', explode('_', $sortAndOrder, -1));
            $order = Str::replaceFirst($sort . '_', '', $sortAndOrder);
            $min_price = $request->get('min_price', $filterPrices['min_price']);
            $max_price = $request->get('max_price', $filterPrices['max_price']);
            $attributes = $request->get('attributes', []);
            return $this->_productRepository->list(locale(), session()->get('currency'), $limit, $offset, $order, $sort, $min_price, $max_price, $attributes, $category['slug']);
        });

        $products = $productList['items'];
        $total = $productList['total'];
        $hasMoreProducts = count($products) < $total;

        return $isAjax ? view('products.ajax.category', compact('products', 'category')) : view('products.category', compact('category', 'categorySlug', 'categories', 'filterPrices', 'attributes', 'products', 'hasMoreProducts'));
    }

    public function detail(string $categorySlug, string $slug)
    {
        try {
            $item = $this->_productRepository->detail(locale(), session()->get('currency'), $slug)['item'];
        } catch (NotFoundException $e) {
            abort(404);
        }

        return view('pages.product', compact('item'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'kw' => 'required'
        ]);

        $keyword = $request->get('kw');

        $products = Cache::rememberForever('products_search_' . $keyword, function () use ($keyword) {
            return $this->_productRepository->search(locale(), session()->get('currency'), $keyword)['items'];
        });

        return view('products.search', compact('products'));
    }
}
