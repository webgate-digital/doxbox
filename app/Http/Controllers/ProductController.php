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
use GoogleTagManager;

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

        $filterPrices = Cache::rememberForever(locale() . '_filter_prices', function () {
            return $this->_productRepository->getFilterPrices(session()->get('currency'))['items'];
        });

        $translations = Cache::rememberForever(locale() . '_translations_web', function () {
            $_translationRepository = new TranslationRepository();
            return $_translationRepository->default(locale())['items'];
        });

        $isAjax = $request->ajax();
        $limit = config('frontstore.defaults.limit.products');
        $page = $isAjax ? $request->get('page', 1) : 0;
        $offset = $isAjax ? ($page - 1) * $limit : 0;

        $setup = Cache::rememberForever(locale() . '_setup', function () {
            return $this->_setupRepository->list()['items'];
        });

        $productList = Cache::rememberForever(locale() . '_products_list_' . base64_encode($request->getRequestUri()), function () use ($request, $filterPrices, $category, $limit, $offset, $setup) {
            $sortAndOrder = $request->get('sort', $setup['api']['defaults']['sort']['products']);
            $sort = implode('_', explode('_', $sortAndOrder, -1));
            $order = Str::replaceFirst($sort . '_', '', $sortAndOrder);
            $min_price = $request->get('min_price', $filterPrices['min_price']);
            $max_price = $request->get('max_price', $filterPrices['max_price']);
            $attributes = $request->get('attributes', []);
            $categorySlug = $category ? $category['slug'] : null;
            $brandSlug = $request->get('znacka', null);
            $flags = ['available_attributes'];
            return $this->_productRepository->list(locale(), session()->get('currency'), $limit, $offset, $order, $sort, $min_price, $max_price, $attributes, $categorySlug, $brandSlug, $flags);
        });

        $attributes = isset($productList['flags']) ? $productList['flags']['available_attributes'] : [];

        $total = $productList['total'];
        $hasMoreProducts = $offset + $limit < $total;
        $availableAttributes = isset($productList['flags']) ? $productList['flags']['available_attributes'] : [];
        $products = $productList['items'];
        $total = $productList['total'];
        $breadcrumbs = self::getBreadcrumbs($category);

        $products = array_map(function ($product) {
            $isSoldOut = $product['count'] <= 0 && $product['is_available_for_order'] == 0;

            // If product is sold out, check if any of its variants is available
            if ($isSoldOut) {
                foreach ($product['variants'] as $variant) {
                    if ($variant['count'] > 0 || $variant['stock_status'] != 'out_of_stock') {
                        $isSoldOut = false;
                        break;
                    }
                }
            }

            $product['is_sold_out'] = $isSoldOut;
            return $product;
        }, $products);

        if (isset($category['children'])) {
            usort($category["children"], function ($a, $b) {
                return $b['score'] <=> $a['score'];
            });
        }

        return $isAjax
            ? response()->json([
                'html' => view('products.ajax.category', compact('products', 'category'))->render(),
                'hasMoreProducts' => $hasMoreProducts,
            ])
            : view('products.category', compact('category', 'categorySlug', 'filterPrices', 'attributes', 'products', 'hasMoreProducts', 'availableAttributes', 'breadcrumbs', 'ogTitle', 'ogDescription'));
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

        $variantsTree = '{}';

        try {
            $variantsTree = $this->_productRepository->variantsTreeV2($item['uuid']);
        } catch (NotFoundException $e) {
            $variantsTree = [];
        }

        $relatedProducts = $item['related_products'] ?? [];

        // If there is less than 4 related products, add random products from the same category
        if (count($relatedProducts) < 4) {
            $relatedProducts = array_merge($relatedProducts, $this->_productRepository->list(locale(), session()->get('currency'), 4, 0, 'desc', 'score', 0, PHP_INT_MAX, [], $item['category']['slug'])['items']);

            // Remove current product from related products
            $relatedProducts = array_filter($relatedProducts, function ($product) use ($item) {
                return $product['uuid'] != $item['uuid'];
            });

            // Remove duplicates (compare by uuid)
            foreach ($relatedProducts as $key => $product) {
                $uuids = array_map(function ($product) {
                    return $product['uuid'];
                }, $relatedProducts);
                $uuids = array_count_values($uuids);
                if ($uuids[$product['uuid']] > 1) {
                    unset($relatedProducts[$key]);
                }
            }

            // Limit to 4 products
            $relatedProducts = array_slice($relatedProducts, 0, 4);
        }

        $breadcrumbs = self::getBreadcrumbs($item['category']);
        $breadcrumbs[] = [
            'title' => $item['name']
        ];

        $isAvailable = $item['count'] > 0 || $item['is_available_for_order'] == 1;

        // If product is not available, look for variants
        if (!$isAvailable) {
            foreach ($item['variants'] as $variant) {
                if ($variant['count'] > 0 || $variant['is_available_for_order'] == 1) {
                    $isAvailable = true;
                    break;
                }
            }
        }

        // Implement dataLayer for Google Tag Manager (ecommerce)
        $categoryString = self::getCategoryChainString($item['category']['slug']);
        $dataLayer = GoogleTagManager::getDataLayer();
        $dataLayer->set('event', 'eec.detail');
        $dataLayer->set('ecommerce', [
            'detail' => [
                'currency' => strtoupper($item['currency']),
                'products' => [
                    [
                        'id' => $item['sku'],
                        'name' => $item['name'],
                        'price' => $item['retail_price'],
                        'category' => $categoryString,
                    ]
                ]
            ]
        ]);

        return view('pages.product', compact('item', 'breadcrumbs', 'isAvailable', 'variantsTree', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'kw' => 'required'
        ]);

        $keyword = $request->get('kw');

        $products = Cache::rememberForever(locale() . '_products_search_' . $keyword, function () use ($keyword) {
            return $this->_productRepository->search(locale(), session()->get('currency'), $keyword)['products'];
        });

        // Handle sold out products
        $products = array_map(function ($product) {
            $isSoldOut = (isset($product['count']) ? $product['count'] : $product['actual_stock_count']) <= 0 && $product['is_available_for_order'] == 0;

            // If product is sold out, check if any of its variants is available
            if ($isSoldOut && isset($product['variants'])) {
                foreach ($product['variants'] as $variant) {
                    if ($variant['count'] > 0 || $variant['is_available_for_order'] == 1) {
                        $isSoldOut = false;
                        break;
                    }
                }
            }

            $product['name'] = isset($product['name']) ? $product['name'] : $product['name_' . locale()];
            $product['is_sold_out'] = $isSoldOut;
            return $product;
        }, $products);

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

        if (!$currentCategory) {
            return false;
        }

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

    public static function getCategoryBy($key, $value)
    {
        $categories = Cache::rememberForever(locale() . '_product_categories', function () {
            $_productRepository = new ProductRepository();
            return $_productRepository->categories(locale())['items'];
        });
        return array_values(array_filter($categories, function ($item) use ($key, $value) {
            return $item[$key] == $value;
        }))[0] ?? null;
    }

    public static function getBreadcrumbs($category) {
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

    public static function buildProductRoute($item)
    {
        $category = $item['category'];
        if (!$category) {
            return "#";
        }
        $categorySlug = isset($item['category']['slug']) ? $item['category']['slug'] : $item['category']['slug_' . locale()];
        $slug = isset($item['slug']) ? $item['slug'] : $item['slug_' . locale()];
        $categorySlugs = self::getCategoriesChainString($categorySlug);
        if (!$categorySlugs) {
            return "#";
        }
        return route(locale() . '.product.detail', [
            'slug' => $slug,
            'categorySlugs' => $categorySlugs
        ]);
    }

    public static function buildCategoryRoute($categorySlug)
    {
        $categorySlugs = self::getCategoriesChainString($categorySlug);
        if (!$categorySlugs) {
            return "#";
        }
        return route(locale() . '.product.category', [
            'categorySlugs' => $categorySlugs
        ]);
    }

    public static function getCategoryChainString($categorySlug, $separator="/"): string
    {
        $category = self::getCategoryBySlug($categorySlug);
        $breadcrumbs = self::getBreadcrumbs($category);
        $categoryChain = array_slice($breadcrumbs, 1); // remove first item (home)
        $categoryNames = array_map(function ($item) {
            return $item['title'];
        }, $categoryChain);
        $categoryString = join($separator, $categoryNames);
        return $categoryString;
    }
}
