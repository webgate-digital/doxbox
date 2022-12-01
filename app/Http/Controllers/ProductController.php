<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\Filter;
use App\Repositories\ProductRepository;
use App\Repositories\SetupRepository;
use App\Repositories\TranslationRepository;
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

    private function handleList(Filter $request, $categorySlugsString = null)
    {
        $category = null;
        $categorySlug = null;
        $ogTitle = 'Produkty';
        $ogDescription = '';
        if ($categorySlugsString) {
            try {
                $categorySlugs = explode('/', $categorySlugsString);
                $categorySlug = end($categorySlugs);
                $category = $this->_productRepository->category(locale(), $categorySlug)['item'];
                $ogTitle = $category['seo_title'] ?? $category['name'];
                $ogDescription = $category['seo_description'] ?? $category['description'];
            } catch (NotFoundException $e) {
                abort(404);
            }
        }

        $categories = Cache::rememberForever(locale() . '_product_categories', function () {
            return $this->_productRepository->categories(locale())['items'];
        });

        $filterPrices = Cache::rememberForever(locale() . '_filter_prices', function () {
            return $this->_productRepository->getFilterPrices(session()->get('currency'))['items'];
        });

        $attributes = Cache::rememberForever(locale() . '_product_attributes', function () {
            return $this->_productRepository->attributes(locale())['items'];
        });

        $translations = Cache::rememberForever(locale() . '_translations_web', function () {
            $_translationRepository = new TranslationRepository();
            return $_translationRepository->default(locale())['items'];
        });

        $isAjax = $request->ajax();
        $limit = config('frontstore.defaults.limit.products');
        $page = $isAjax ? $request->get('page', 1) : 0;
        $offset = $isAjax ? ($page - 1) * $limit : 0;
        $productList = Cache::rememberForever(locale() . '_products_list_' . base64_encode($request->getRequestUri()), function () use ($request, $filterPrices, $category, $limit, $offset) {
            $setup = Cache::rememberForever(locale() . '_setup', function () {
                return $this->_setupRepository->list()['items'];
            });
            $sortAndOrder = $request->get('sort', $setup['api']['defaults']['sort']['products']);
            $sort = implode('_', explode('_', $sortAndOrder, -1));
            $order = Str::replaceFirst($sort . '_', '', $sortAndOrder);
            $min_price = $request->get('min_price', $filterPrices['min_price']);
            $max_price = $request->get('max_price', $filterPrices['max_price']);
            $attributes = $request->get('attributes', []);
            $categorySlug = $category ? $category['slug'] : null;
            return $this->_productRepository->list(locale(), session()->get('currency'), $limit, $offset, $order, $sort, $min_price, $max_price, $attributes, $categorySlug);
        });

        $availableAttributes = $productList['availableAttributes'];

        $products = $productList['items'];
        $total = $productList['total'];
        $hasMoreProducts = count($products) < $total;
        $breadcrumbs = $this->getBreadcrumbs($category);

        return $isAjax
            ? view('products.ajax.category', compact('products', 'category'))
            : view('products.category', compact('category', 'categorySlug', 'categories', 'filterPrices', 'attributes', 'products', 'hasMoreProducts', 'availableAttributes', 'breadcrumbs', 'ogTitle', 'ogDescription'));
    }
    
    public function category(Filter $request, string $categorySlugs)
    {
        return $this->handleList($request, $categorySlugs);
    }

    public function list(Filter $request)
    {
        return $this->handleList($request);
    }

    public function detail(string $categories, string $slug)
    {
        try {
            $item = $this->_productRepository->detail(locale(), session()->get('currency'), $slug);
        } catch (NotFoundException $e) {
            abort(404);
        }
        $breadcrumbs = $this->getBreadcrumbs($item['item']['category']);
        $breadcrumbs[] = [
            'title' => $item['item']['name']
        ];

        return view('pages.product', compact('item', 'breadcrumbs'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'kw' => 'required'
        ]);

        $keyword = $request->get('kw');

        $products = Cache::rememberForever(locale() . '_products_search_' . $keyword, function () use ($keyword) {
            return $this->_productRepository->search(locale(), session()->get('currency'), $keyword)['items'];
        });

        return view('products.search', compact('products'));
    }

    public static function getCategoriesChainString($categorySlug): string
    {
        $categories = Cache::rememberForever(locale() . '_product_categories', function () {
            $_productRepository = new ProductRepository();
            return $_productRepository->categories(locale())['items'];
        });

        $productCategorySlugs = [];
        $currentCategory = self::getCategoryBySlug($categorySlug);
        do {
            $productCategorySlugs[] = $currentCategory['slug'];
            $currentCategory = array_values(array_filter($categories, function ($item) use ($currentCategory) {
                return $item['uuid'] == $currentCategory['parent_uuid'];
            }))[0] ?? null;
        } while ($currentCategory);

        return join('/', array_reverse($productCategorySlugs));
    }

    public static function getCategoryBySlug(string $categorySlug)
    {
        $categories = Cache::rememberForever(locale() . '_product_categories', function () {
            $_productRepository = new ProductRepository();
            return $_productRepository->categories(locale())['items'];
        });
        return array_values(array_filter($categories, function ($item) use ($categorySlug) {
            return $item['slug'] == $categorySlug;
        }))[0] ?? null;
    }

    public function getBreadcrumbs($category) {
        $categories = Cache::rememberForever(locale() . '_product_categories', function () {
            $_productRepository = new ProductRepository();
            return $_productRepository->categories(locale())['items'];
        });
        
        $translations = Cache::rememberForever(locale() . '_translations_web', function () {
            $_translationRepository = new TranslationRepository();
            return $_translationRepository->default(locale())['items'];
        });

        $breadcrumbs = [];
        if ($category) {
            do {
                $breadcrumbs[] = [
                    'url' => self::buildCategoryRoute($category['slug']),
                    'title' => $category['name'],
                ];
                $category = array_values(array_filter($categories, function ($item) use ($category) {
                    return $item['uuid'] == $category['parent_uuid'];
                }))[0] ?? null;
            } while ($category);
        }

        $breadcrumbs[] = [
            'url' => route(locale() . '.product.list'),
            'title' => $translations['menu.products']['text'],
        ];

        return array_reverse($breadcrumbs);
    }

    public static function buildProductRoute($categorySlug, string $slug)
    {
        return route(locale() . '.product.detail', [
            'slug' => $slug,
            'categorySlugs' => self::getCategoriesChainString($categorySlug)
        ]);
    }

    public static function buildCategoryRoute($categorySlug)
    {
        return route(locale() . '.product.category', [
            'categorySlugs' => self::getCategoriesChainString($categorySlug)
        ]);
    }
}
