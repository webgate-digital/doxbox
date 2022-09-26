<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Repositories\PageRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TranslationRepository;
use Cache;
use Exception;
use Illuminate\Http\Request;

const HOUR = 3600;

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
        $products = Cache::remember('homepage_products_list', HOUR, function () {
            return $this->_productRepository->list(locale(), session()->get('currency'), 8)['items'];
        });

        $categories = Cache::remember('homepage_categories_list', HOUR, function () {
            return $this->_productRepository->categories(locale(), 8)['items'];
        });

        $brands = Cache::rememberForever('homepage_brands_list', function () {
            return $this->_productRepository->brands(locale(), 8)['items'];
        });

        return view('pages.homepage', compact('products', 'categories', 'brands'));
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

        $translations = Cache::rememberForever('translations_web', function () {
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

        session()->forget('order');
        $params = $request->all();

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
        $mailchimpApiKey = env('MAILCHIMP_API_KEY');
        $mailchimpListId = env('MAILCHIMP_LIST_ID');
        $mailchimpApiEndpoint = env('MAILCHIMP_API_ENDPOINT');
        
        $email = $request->get('email', null);
        
        abort_if(!$email || !$mailchimpApiKey || !$mailchimpListId, 400);

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', $mailchimpApiEndpoint . '/lists/' . $mailchimpListId . '/members', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode('user:' . $mailchimpApiKey),
                ],
                'json' => [
                    'email_address' => $email,
                    'status' => 'subscribed',
                ],
            ]);

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
}
