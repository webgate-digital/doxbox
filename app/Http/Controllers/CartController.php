<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use App\Http\Requests\AddToCart;
use App\Http\Requests\ApplyVoucher;
use App\Http\Requests\Checkout;
use App\Http\Requests\RemoveFromCart;
use App\Repositories\CartRepository;
use App\Repositories\PageRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SettingRepository;
use App\Repositories\SetupRepository;
use App\Repositories\TranslationRepository;
use App\Services\CartService;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Str;
use Exception;
use GoogleTagManager;

class CartController extends Controller
{
    private $_cartService;
    private $_productRepository;
    private $_setupRepository;
    private $_pageRepository;
    private $_cartRepository;

    public function __construct(CartService $cartService, ProductRepository $productRepository, SetupRepository $setupRepository, PageRepository $pageRepository, CartRepository $cartRepository)
    {
        $this->_cartService = $cartService;
        $this->_productRepository = $productRepository;
        $this->_setupRepository = $setupRepository;
        $this->_pageRepository = $pageRepository;
        $this->_cartRepository = $cartRepository;
    }

    public function index()
    {
        $voucher = session()->get('voucher', null);
        $shippingCountries = Cache::rememberForever(locale() . '_shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });
        $shippingCountry = $shippingCountries[session()->get('shipping_country')];

        $checkoutSupportValue = session()->get('checkout_support_value', 0);
        $checkoutSupportName = session()->get('checkout_support_name', null);

        $cart = $this->_cartService->update(locale(), session()->get('currency'), session()->get('cart', []), session()->get('multipack', []), $voucher['code'] ?? null, $shippingCountry['uuid'], session()->get('shipping_type', null), session()->get('payment_type', null), ['value' => $checkoutSupportValue, 'name' => $checkoutSupportName], session()->get('variants', []));

        $upsell = [];

        if ($cart['count']) {
            $upsell = $this->_productRepository->upsell(locale(), session()->get('currency'), array_values(session()->get('cart', [])))['items'];
        }

        $step = 1;

        // Implement dataLayer for Google Tag Manager (ecommerce)
        $dataLayer = GoogleTagManager::getDataLayer();
        $dataLayer->set('event', 'eec.checkout');
        $dataLayer->set('ecommerce', [
            'currencyCode' => session()->get('currency'),
            'checkout' => [
                'actionField' => [
                    'step' => $step,
                    'option' => $shippingCountry['name'],
                ],
                'products' => array_map(function ($item) {
                    $categoryString = \App\Http\Controllers\ProductController::getCategoryChainString($item['meta']['category_slug']);
                    return [
                        'id' => $item['meta']['sku'],
                        'name' => $item['meta']['name'],
                        'price' => $item['meta']['price'],
                        'category' => $categoryString,
                        'quantity' => $item['count'],
                    ];
                }, $cart['items'] ?? []),
            ],
        ]);

        return view('cart.index', compact('cart', 'upsell', 'shippingCountry', 'voucher', 'step'));
    }

    public function applyVoucher(ApplyVoucher $request)
    {
        $this->removeVoucher(false);

        $translations = \Cache::rememberForever(locale() . '_translations_web', function () {
            $_translationRepository = new TranslationRepository();
            return $_translationRepository->default(locale())['items'];
        });

        try {
            $voucher = $this->_setupRepository->voucherAvailability($request->get('voucher_code'), session()->get('currency'))['item'];
        } catch (NotFoundException $e) {
            return redirect()->back()->with(['error' => $translations['cart.voucher_error']['text']]);
        }

        session()->put('voucher', $voucher);

        return redirect()->back()->with(['success' => $translations['cart.voucher_success']['text']]);
    }

    public function removeVoucher(bool $redirect = true)
    {
        session()->forget('voucher');

        $translations = \Cache::rememberForever(locale() . '_translations_web', function () {
            $_translationRepository = new TranslationRepository();
            return $_translationRepository->default(locale())['items'];
        });

        if ($redirect) {
            return redirect()->back()->with(['success' => $translations['cart.voucher_remove_success']['text']]);
        }
    }

    public function initShipping()
    {
        $shippingCountries = Cache::rememberForever(locale() . '_shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });

        $shippingTypes = $this->_setupRepository->getShippingTypes(locale(), session()->get('currency'), session()->get('shipping_country'))['items'];

        if (!session()->has('shipping_type')) {
            $paymentTypes = $this->_setupRepository->getPayments(locale(), session()->get('currency'), session()->get('shipping_country'))['items'];
        } else {
            $paymentTypes = $this->_setupRepository->getPaymentTypes(locale(), session()->get('currency'), session()->get('shipping_country'), session()->get('shipping_type'))['items'];
        }

        $packetaSettings = Cache::rememberForever(locale() . '_packeta_settings', function () {
            $_settingRepository = new SettingRepository();
            return $_settingRepository->packeta()['items'];
        });

        return [
            'shippingCountries' => $shippingCountries,
            'shippingCountry' => session()->get('shipping_country'),
            'shippingTypes' => $shippingTypes,
            'shippingType' => session()->get('shipping_type'),
            'packetaSelectorBranchName' => session()->get('packeta-selector-branch-name'),
            'packetaSelectorBranchId' => session()->get('packeta-selector-branch-id'),
            'ulozenkaBranch' => session()->get('ulozenka-branch'),
            'paymentTypes' => $paymentTypes,
            'paymentType' => session()->get('payment_type'),
            'packetaApiKey' => $packetaSettings['api_key']['value']
        ];
    }

    public function shippingAndPayment()
    {
        $shippingCountries = Cache::rememberForever(locale() . '_shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });

        $cart = $this->_cartService->update(locale(), session()->get('currency'), session()->get('cart', []), session()->get('multipack', []), session()->get('voucher', null)['code'] ?? null, $shippingCountries[session()->get('shipping_country')]['uuid'], session()->get('shipping_type', null), session()->get('payment_type', null), ['value' => session()->get('checkout_support_value', 0), 'name' => session()->get('checkout_support_name', null)], session()->get('variants', []));

        $step = 2;

        return view('cart.shipping_and_payment', compact('cart', 'step'));
    }

    public function selectShippingCountry(Request $request)
    {
        $shippingCountries = Cache::rememberForever(locale() . '_shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });

        $request->validate([
            'default_shipping_country' => 'required|in:' . implode(',', array_keys($shippingCountries))
        ]);

        session()->put('shipping_country', $request->get('default_shipping_country'));
        session()->forget('shipping_type');
        session()->forget('payment_type');
        session()->forget('ulozenka-branch');
        session()->forget('packeta-selector-branch-name');
        session()->forget('packeta-selector-branch-id');
        session()->forget('checkout_support_value');
        session()->forget('checkout_support_name');
    }

    public function selectShipping(Request $request)
    {
        $request->validate([
            'shipping_type' => 'required',
        ]);

        $shippingType = $request->get('shipping_type');

        $request->validate([
            'packeta-selector-branch-name' => Rule::requiredIf(function () use ($shippingType) {
                return Str::contains($shippingType, 'PACKETA');
            }),
            'packeta-selector-branch-id' => Rule::requiredIf(function () use ($shippingType) {
                return Str::contains($shippingType, 'PACKETA');
            }),
            'ulozenka-branch' => Rule::requiredIf(function () use ($shippingType) {
                return Str::contains($shippingType, 'ULOZENKA');
            }),
        ]);

        session()->forget('payment_type');
        session()->forget('checkout_support_value');
        session()->forget('checkout_support_name');
        session()->put('shipping_type', $shippingType);

        if (Str::contains($request->get('shipping_type'), 'PACKETA')) {
            session()->put('packeta-selector-branch-name', $request->get('packeta-selector-branch-name'));
            session()->put('packeta-selector-branch-id', $request->get('packeta-selector-branch-id'));
        } else {
            session()->forget('packeta-selector-branch-name');
            session()->forget('packeta-selector-branch-id');
        }

        if (Str::contains($shippingType, 'ULOZENKA')) {
            session()->put('ulozenka-branch', $request->get('ulozenka-branch'));
        } else {
            session()->forget('ulozenka-branch');
        }
    }

    public function selectPayment(Request $request)
    {
        $request->validate([
            'payment_type' => 'required',
        ]);

        session()->put('payment_type', $request->get('payment_type'));
        session()->forget('checkout_support_value');
        session()->forget('checkout_support_name');
    }

    public function continueToCheckout(Request $request)
    {
        $request->validate([
            'toc' => 'accepted',
        ]);

        session()->put('toc_accepted', true);

        return route(locale() . '.cart.checkout');
    }

    public function checkout()
    {
        $voucher = session()->get('voucher', null);
        $shippingCountries = Cache::rememberForever(locale() . '_shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });
        $shippingCountry = $shippingCountries[session()->get('shipping_country')];
        $checkoutSupportValue = session()->get('checkout_support_value', 0);
        $checkoutSupportName = session()->get('checkout_support_name', '');

        $cart = $this->_cartService->update(locale(), session()->get('currency'), session()->get('cart', []), session()->get('multipack', []), $voucher['code'] ?? null, $shippingCountry['uuid'], session()->get('shipping_type', null), session()->get('payment_type', null), ['value' => $checkoutSupportValue, 'name' => $checkoutSupportName], session()->get('variants', []);
        $user = session()->get('me', null);

        $checkoutSupportPage = Cache::rememberForever(locale() . '_page_' . 'prispevky-pre-utulky', function () {
            try {
                return $this->_pageRepository->detail(locale(), session()->get('currency'), 'prispevky-pre-utulky')['item'];
            } catch (NotFoundException $e) {
                return null;
            }
        });

        $step = 3;

        return view('cart.checkout', compact('cart', 'user', 'voucher', 'shippingCountry', 'checkoutSupportPage', 'step'));
    }

    public function order(Checkout $request)
    {
        $voucher = session()->get('voucher', null);
        $shippingCountries = Cache::rememberForever(locale() . '_shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });
        $shippingCountry = $shippingCountries[session()->get('shipping_country')];
        $checkoutSupportValue = session()->get('checkout_support_value', 0);
        $checkoutSupportName = session()->get('checkout_support_name', '');

        $cart = $this->_cartService->update(locale(), session()->get('currency'), session()->get('cart', []), session()->get('multipack', []), $voucher['code'] ?? null, $shippingCountry['uuid'], session()->get('shipping_type', null), session()->get('payment_type', null), ['value' => $checkoutSupportValue, 'name' => $checkoutSupportName], session()->get('variants', []);

        $data = $request->validated();
        $data['currency'] = session()->get('currency');
        $data['locale'] = locale();
        $data['shipping_country'] = session()->get('shipping_country');
        $data['shipping_type'] = session()->get('shipping_type');
        $data['meta']['packeta-selector-branch-name'] = session()->get('packeta-selector-branch-name', null);
        $data['meta']['packeta-selector-branch-id'] = session()->get('packeta-selector-branch-id', null);
        $data['meta']['ulozenka-branch'] = session()->get('ulozenka-branch', null);
        $data['payment_type'] = session()->get('payment_type');
        $data['success_url'] = route(locale() . '.thank_you');
        $data['error_url'] = route(locale() . '.cart.checkout') . '?error=true';
        $data['cart'] = $cart;

        $agreedToNewsletter = $request->get('newsletter', false);
        if ($agreedToNewsletter) {
            \App\Http\Controllers\PageController::registerToNewsletter($request->get('email'));
        }

        try {
            $order = $this->_cartRepository->checkout($data);
            \session()->put('order', $order['item']);
            \session()->forget([
                'shipping_type',
                'payment_type',
                'cart',
                'voucher',
                'checkout_support_value',
                'checkout_support_name',
                'multipack',
                'toc_accepted'
            ]);

            return redirect()->to($order['redirect']);
        } catch (ValidationException | UnauthorizedException | NotFoundException | Exception $e) {
            return redirect()->to($data['error_url']);
        }
    }

    //    API

    public function add(AddToCart $request)
    {
        $uuid = $request->get('uuid');
        $quantity = $request->get('quantity', 1);
        $product = $this->_cartService->add($uuid, $quantity);
        if ($product === false) {
            abort(400);
        }

        $categoryString = \App\Http\Controllers\ProductController::getCategoryChainString($product['category']['slug']);
        $response = [
            'quantity' => $quantity,
            'sku' => $product['sku'],
            'currency' => $product['currency'],
            'name' => $product['name'],
            'retail_price' => $product['retail_price'],
            'category' => [
                'name' => $product['category']['name'],
                'slug' => $product['category']['slug'],
            ],
            'category_path' => $categoryString,
        ];

        return response()->json($response);
    }

    public function update()
    {
        $voucher = session()->get('voucher', null);
        $shippingCountries = Cache::rememberForever(locale() . '_shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });
        $shippingCountry = $shippingCountries[session()->get('shipping_country')];
        $checkoutSupportValue = session()->get('checkout_support_value', 0);
        $checkoutSupportName = session()->get('checkout_support_name', null);

        return $this->_cartService->update(locale(), session()->get('currency'), session()->get('cart', []), session()->get('multipack', []), $voucher['code'] ?? null, $shippingCountry['uuid'], session()->get('shipping_type', null), session()->get('payment_type', null), ['value' => $checkoutSupportValue, 'name' => $checkoutSupportName], session()->get('variants', []));
    }

    public function delete(RemoveFromCart $request)
    {
        $this->_cartService->delete($request->get('uuid'));
    }

    public function remove(RemoveFromCart $request)
    {
        $this->_cartService->remove($request->get('uuid'));
    }

    public function addVariant(AddToCart $request)
    {
        if (!$this->_cartService->addVariant($request->get('uuid'), $request->get('quantity'))) {
            abort(400);
        }
    }

    public function deleteVariant(RemoveFromCart $request)
    {
        $this->_cartService->deleteVariant($request->get('uuid'));
    }

    public function removeVariant(RemoveFromCart $request)
    {
        $this->_cartService->removeVariant($request->get('uuid'));
    }
}
