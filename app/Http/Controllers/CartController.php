<?php

namespace App\Http\Controllers;

use Cache;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Str;
use Theshop\Frontstore\Base\Exceptions\NotFoundException;
use Theshop\Frontstore\Base\Exceptions\UnauthorizedException;
use Theshop\Frontstore\Base\Exceptions\ValidationException;
use Theshop\Frontstore\Base\Repositories\SettingRepository;
use Theshop\Frontstore\Base\Repositories\SetupRepository;
use Theshop\Frontstore\Base\Repositories\TranslationRepository;
use Theshop\Frontstore\Cart\Repositories\CartRepository;
use Theshop\Frontstore\Cart\Requests\AddToCart;
use Theshop\Frontstore\Cart\Requests\ApplyVoucher;
use Theshop\Frontstore\Cart\Requests\Checkout;
use Theshop\Frontstore\Cart\Requests\RemoveFromCart;
use Theshop\Frontstore\Cart\Services\CartService;
use Theshop\Frontstore\Pages\Repositories\PageRepository;
use Theshop\Frontstore\Products\Repositories\ProductRepository;
use Exception;

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
        $shippingCountries = Cache::rememberForever('shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });
        $shippingCountry = $shippingCountries[session()->get('shipping_country')];

        $checkoutSupportValue = session()->get('checkout_support_value', 0);
        $checkoutSupportName = session()->get('checkout_support_name', null);

        $cart = $this->_cartService->update(locale(), session()->get('currency'), session()->get('cart', []), session()->get('multipack', []), $voucher['code'] ?? null, $shippingCountry['uuid'], session()->get('shipping_type', null), session()->get('payment_type', null), ['value' => $checkoutSupportValue, 'name' => $checkoutSupportName]);

        $upsell = [];

        if ($cart['count']) {
            $upsell = $this->_productRepository->upsell(locale(), session()->get('currency'), array_values(session()->get('cart', [])))['items'];
        }

        return view('cart.index', compact('cart', 'upsell', 'shippingCountry', 'voucher'));
    }

    public function applyVoucher(ApplyVoucher $request)
    {
        $this->removeVoucher(false);

        $translations = \Cache::rememberForever('translations_web', function () {
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

        $translations = \Cache::rememberForever('translations_web', function () {
            $_translationRepository = new TranslationRepository();
            return $_translationRepository->default(locale())['items'];
        });

        if ($redirect) {
            return redirect()->back()->with(['success' => $translations['cart.voucher_remove_success']['text']]);
        }
    }

    public function shippingAndPayment()
    {
        $voucher = session()->get('voucher', null);
        $shippingCountries = Cache::rememberForever('shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });
        $shippingCountry = $shippingCountries[session()->get('shipping_country')];

        $checkoutSupportValue = session()->get('checkout_support_value', 0);
        $checkoutSupportName = session()->get('checkout_support_name', null);

        $cart = $this->_cartService->update(locale(), session()->get('currency'), session()->get('cart', []), session()->get('multipack', []), $voucher['code'] ?? null, $shippingCountry['uuid'], session()->get('shipping_type', null), session()->get('payment_type', null), ['value' => $checkoutSupportValue, 'name' => $checkoutSupportName]);

        $shippingTypes = $this->_setupRepository->getShippingTypes(locale(), session()->get('currency'), session()->get('shipping_country'))['items'];

        if (!session()->has('shipping_type')) {
            $paymentTypes = $this->_setupRepository->getPayments(locale(), session()->get('currency'), session()->get('shipping_country'))['items'];
        } else {
            $paymentTypes = $this->_setupRepository->getPaymentTypes(locale(), session()->get('currency'), session()->get('shipping_country'), session()->get('shipping_type'))['items'];
        }

        $packetaSettings = Cache::rememberForever('packeta_settings', function () {
            $_settingRepository = new SettingRepository();
            return $_settingRepository->packeta()['items'];
        });

        return view('cart.shipping_and_payment', compact('shippingCountries', 'shippingCountry', 'shippingTypes', 'paymentTypes', 'cart', 'packetaSettings'));
    }

    public function selectShippingCountry(Request $request)
    {
        $shippingCountries = Cache::rememberForever('shipping_countries', function () {
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

        return redirect()->back();
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

        return redirect()->to(url()->previous());
    }

    public function selectPayment(Request $request)
    {
        $request->validate([
            'payment_type' => 'required',
        ]);

        session()->put('payment_type', $request->get('payment_type'));
        session()->forget('checkout_support_value');
        session()->forget('checkout_support_name');

        return redirect()->back();
    }

    public function continueToCheckout(Request $request)
    {
        $request->validate([
            'toc' => 'accepted',
        ]);

        return redirect()->route(locale() . '.cart.checkout');
    }

    public function checkout()
    {
        $voucher = session()->get('voucher', null);
        $shippingCountries = Cache::rememberForever('shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });
        $shippingCountry = $shippingCountries[session()->get('shipping_country')];
        $checkoutSupportValue = session()->get('checkout_support_value', 0);
        $checkoutSupportName = session()->get('checkout_support_name', '');

        $cart = $this->_cartService->update(locale(), session()->get('currency'), session()->get('cart', []), session()->get('multipack', []), $voucher['code'] ?? null, $shippingCountry['uuid'], session()->get('shipping_type', null), session()->get('payment_type', null), ['value' => $checkoutSupportValue, 'name' => $checkoutSupportName]);
        $user = null;

        $checkoutSupportPage = Cache::rememberForever('page_' . 'prispevky-pre-utulky', function () {
            try {
                return $this->_pageRepository->detail(locale(), session()->get('currency'), 'prispevky-pre-utulky')['item'];
            } catch (NotFoundException $e) {
                return null;
            }
        });

        return view('cart.checkout', compact('cart', 'user', 'voucher', 'shippingCountry', 'checkoutSupportPage'));
    }

    public function order(Checkout $request)
    {
        $voucher = session()->get('voucher', null);
        $shippingCountries = Cache::rememberForever('shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });
        $shippingCountry = $shippingCountries[session()->get('shipping_country')];
        $checkoutSupportValue = session()->get('checkout_support_value', 0);
        $checkoutSupportName = session()->get('checkout_support_name', '');

        $cart = $this->_cartService->update(locale(), session()->get('currency'), session()->get('cart', []), session()->get('multipack', []), $voucher['code'] ?? null, $shippingCountry['uuid'], session()->get('shipping_type', null), session()->get('payment_type', null), ['value' => $checkoutSupportValue, 'name' => $checkoutSupportName]);

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
                'multipack'
            ]);
            session()->forget('ulozenka-branch');
            session()->forget('packeta-selector-branch-name');
            session()->forget('packeta-selector-branch-id');

            return redirect()->to($order['redirect']);
        } catch (ValidationException | UnauthorizedException | NotFoundException | Exception $e) {
            return redirect()->to($data['error_url']);
        }
    }

    //    API

    public function add(AddToCart $request)
    {
        if (!$this->_cartService->add($request->get('uuid'), $request->get('quantity'))) {
            abort(400);
        }
    }

    public function update()
    {
        $voucher = session()->get('voucher', null);
        $shippingCountries = Cache::rememberForever('shipping_countries', function () {
            return $this->_setupRepository->getShippingCountries(locale(), session()->get('currency'))['items'];
        });
        $shippingCountry = $shippingCountries[session()->get('shipping_country')];
        $checkoutSupportValue = session()->get('checkout_support_value', 0);
        $checkoutSupportName = session()->get('checkout_support_name', null);

        return $this->_cartService->update(locale(), session()->get('currency'), session()->get('cart', []), session()->get('multipack', []), $voucher['code'] ?? null, $shippingCountry['uuid'], session()->get('shipping_type', null), session()->get('payment_type', null), ['value' => $checkoutSupportValue, 'name' => $checkoutSupportName]);
    }

    public function delete(RemoveFromCart $request)
    {
        $this->_cartService->delete($request->get('uuid'));
    }

    public function remove(RemoveFromCart $request)
    {
        $this->_cartService->remove($request->get('uuid'));
    }
}
