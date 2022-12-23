<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Repositories\PageRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SettingRepository;
use App\Repositories\SetupRepository;
use App\Repositories\TranslationRepository;
use Cache;
use Exception;
use Illuminate\Http\Request;
use GoogleTagManager;

const HOUR = 3600;

class PageController extends Controller
{
    private $_productRepository;
    private $_pageRepository;
    private $_setupRepository;
    private $_cartRepository;

    public function __construct(ProductRepository $productRepository, PageRepository $pageRepository, SetupRepository $setupRepository, CartRepository $cartRepository)
    {
        $this->_productRepository = $productRepository;
        $this->_pageRepository = $pageRepository;
        $this->_setupRepository = new SetupRepository();
        $this->_cartRepository = $cartRepository;
    }

    public function homepage()
    {
        $products = Cache::remember(locale() . '_homepage_products_list', HOUR, function () {
            
            return $this->_productRepository->list(locale(), session()->get('currency'), 8)['items'];
        });

        $categories = Cache::remember(locale() . '_homepage_categories_list', HOUR, function () {
            return $this->_productRepository->categories(locale(), 12)['items'];
        });

        $brands = Cache::rememberForever(locale() . '_homepage_brands_list', function () {
            return $this->_productRepository->brands(locale(), 8)['items'];
        });

        return view('pages.homepage', compact('products', 'categories', 'brands'));
    }

    public function page(string $slug)
    {
        $item = Cache::rememberForever(locale() . '_page_' . $slug, function () use ($slug) {
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
        $supplier = Cache::rememberForever(locale() . '_blog_articles', function () {
            $_settingRepository = new SettingRepository();
            return $_settingRepository->supplier()['items'];
        });

        return view('pages.blog', compact('supplier'));
    }

    public function contact()
    {
        $supplier = Cache::rememberForever(locale() . '_supplier_settings', function () {
            $_settingRepository = new SettingRepository();
            return $_settingRepository->supplier()['items'];
        });

        $translations = Cache::rememberForever(locale() . '_translations_web', function () {
            $_translationRepository = new TranslationRepository();
            return $_translationRepository->default(locale())['items'];
        });

        $faqQuestionTranslations = array_filter($translations, function ($key) {
            return substr($key, 0, 13) === 'faq.question.';
        }, ARRAY_FILTER_USE_KEY);

        $faqAnswerTranslations = array_filter($translations, function ($key) {
            return substr($key, 0, 11) === 'faq.answer.';
        }, ARRAY_FILTER_USE_KEY);

        $faqItems = array_map(function ($key) use ($faqQuestionTranslations, $faqAnswerTranslations) {
            $questionNumber = substr($key, 13);
            if (isset($faqQuestionTranslations['faq.question.' . $questionNumber]) && isset($faqAnswerTranslations['faq.answer.' . $questionNumber])) {
                return [
                    'question' => $faqQuestionTranslations['faq.question.' . $questionNumber]['text'],
                'answer' => $faqAnswerTranslations['faq.answer.' . $questionNumber]['text'],
                ];
            } else {
                return null;
            }
        }, array_keys($faqQuestionTranslations));

        $faqItems = array_filter($faqItems);

        return view('pages.contact', compact('supplier', 'faqItems'));
    }

    public function thankYou(Request $request)
    {
        $order = session()->get('order', null);

        if (!$order) {
            abort(404);
        }

        $order['packeta-selector-branch-name'] = session()->get('packeta-selector-branch-name');
        session()->forget('order');
        session()->forget('packeta-selector-branch-name');
        $params = $request->all();
        $paymentUUID = $order['payment']['uuid'];

        // Validate payment if PayPal
        $isPayPal = strpos(strtolower($paymentUUID), 'paypal') !== false;
        if ($isPayPal && $params['token'] && $params['PayerID']) {
            $this->_cartRepository->validatePayPalPayment([
                'TID' => $tid,
                'TOKEN' => $params['token'],
                'PAYERID' => $params['PayerID'],
                'AMOUNT' => $order['vat_amount'],
                'CURRENCY' => strtoupper($order['currency']),
            ]);
        }

        // Implement dataLayer for Google Tag Manager (ecommerce)
        $dataLayer = GoogleTagManager::getDataLayer();
        $dataLayer->set('event', 'eec.purchase');

        $actionField = [
            'id' => $order['code'],
            'revenue' => number_format($order['vat_amount'], 2, '.', ''),
            'tax' => number_format($order['vat_value'], 2, '.', ''),
            'shipping' => number_format($order['shipping']['price'], 2, '.', ''),
        ];

        if (isset($order['voucher_code']) && $order['voucher_code']) {
            $actionField['coupon'] = $order['voucher_code'];
        }

        $products = array_filter($order['items'], function($item) {
            return $item['type'] === 'PRODUCT';
        });

        $dataLayer->set('ecommerce', [
            'currencyCode' => strtoupper($order['currency']),
            'purchase' => [
                'actionField' => $actionField,
                'products' => array_map(function ($item) {
                    $category = \App\Http\Controllers\ProductController::getCategoryBy('name', $item['product_category_name']);
                    $categoryChainString = \App\Http\Controllers\ProductController::getCategoryChainString($category['slug']);
                    return [
                        'name' => $item['name'],
                        'id' => $item['sku'],
                        'price' => number_format($item['price'], 2, '.', ''),
                        'category' => $categoryChainString,
                        'quantity' => $item['quantity'],
                    ];
                }, $products),
            ],
        ]);

        return view('pages.thank_you', compact('order', 'params'));
    }

    public function resetCache(Request $request)
    {
        abort_if($request->get('token', null) !== config('frontstore.cacheResetToken'), 400);

        try {
            \cache()->flush();
        } catch (Exception $e) {
        }
    }

    public function newsletter(Request $request)
    {   
        $email = $request->get('email', null);
        abort_if(!$email, 400);

        try {
            $response = $this->registerToNewsletter($email);

            return response()->json([
                'status' => 'success',
                'message' => 'Úspešne ste boli prihlásený na odber noviniek.',
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();

            \Log::error('Newsletter subscription failed:', [
                'email' => $email,
                'response' => $response->getBody()->getContents(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Prihlásenie na odber noviniek zlyhalo. Prosím skúste to znova.',
            ]);
        }

        return response()->json(['success' => true]);
    }

    public static function registerToNewsletter($email)
    {
        $mailchimpApiKey = env('MAILCHIMP_API_KEY');
        $mailchimpListId = env('MAILCHIMP_LIST_ID');
        $mailchimpApiEndpoint = env('MAILCHIMP_API_ENDPOINT');
        
        if (!$mailchimpApiKey || !$mailchimpListId) {
            return;
        }

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', $mailchimpApiEndpoint . '/lists/' . $mailchimpListId . '/members', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode('user:' . $mailchimpApiKey),
            ],
            'json' => [
                'email_address' => $email,
                'status' => 'subscribed',
            ],
        ]);

        return $response;
    }

    public function facebookXML()
    {
        $xml = Cache::rememberForever(locale() . '_facebook_xml', function () {
            $productUrl = route(locale() . '.product.detail', ['categorySlugs' => '--CATEGORY--', 'slug' => '--PRODUCT--']);
            return $this->_setupRepository->facebookXML(locale(), session()->get('currency'), $productUrl);
        });

        $content = $xml['content'];
        $header = $xml['header'];

        return response()->view('facebook', compact('header', 'content'))->header('Content-Type', 'text/xml');
        return $view;
    }
}
