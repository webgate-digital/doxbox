<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/cart/add', [CartController::class, 'add']);
Route::delete('/cart/delete', [CartController::class, 'delete']);
Route::delete('/cart/remove', [CartController::class, 'remove']);
Route::get('/cart/update', [CartController::class, 'update']);

Route::group(['middleware' => ['empty.cart']], function () {

    Route::multilingual('/voucher/remove', [CartController::class, 'removeVoucher'])->name('remove_voucher')->method('delete');
    Route::multilingual('/voucher/apply', [CartController::class, 'applyVoucher'])->name('apply_voucher')->method('post');

    Route::multilingual('/shipping/init', [CartController::class, 'initShipping'])->name('cart.shipping.init');
    Route::multilingual('/shipping-and-payment', [CartController::class, 'shippingAndPayment'])->name('cart.shipping');

    Route::multilingual('/select-payment', [CartController::class, 'selectPayment'])->name('cart.select_payment')->method('post');
    Route::multilingual('/select-shipping', [CartController::class, 'selectShipping'])->name('cart.select_shipping')->method('post');
    Route::multilingual('/select-shipping-country', [CartController::class, 'selectShippingCountry'])->name('cart.select_shipping_country')->method('post');
    Route::multilingual('/continue-to-checkout', [CartController::class, 'continueToCheckout'])->name('cart.continue_to_checkout')->method('post');

    Route::group(['middleware' => ['empty.shipping']], function () {

        Route::multilingual('/order', [CartController::class, 'order'])->name('cart.order')->method('post');
        Route::multilingual('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    });
});

Route::multilingual('/categories', [PageController::class, 'categories'])->name('categories');
Route::multilingual('/brands', [PageController::class, 'brands'])->name('brands');

Route::multilingual('/cart', [CartController::class, 'index'])->name('cart');

Route::multilingual('/thank-you', [PageController::class, 'thankYou'])->name('thank_you');

Route::multilingual('/product-detail', [ProductController::class, 'detail'])
    ->where('categorySlugs', '^[a-zA-Z0-9-_\/]+$')
    ->name('product.detail');
Route::multilingual('/product-category', [ProductController::class, 'category'])
    ->where('categorySlugs', '^[a-zA-Z0-9-_\/]+$')
    ->name('product.category');
Route::multilingual('/product-brand', [ProductController::class, 'brand'])
    ->where('brandSlug', '^[a-zA-Z0-9-_\/]+$')
    ->name('product.brand');
Route::multilingual('/product-list', [ProductController::class, 'list'])->name('product.list');
Route::multilingual('/search', [ProductController::class, 'search'])->name('search');

Route::multilingual('/blog-detail', [BlogController::class, 'detail'])->name('blog.detail');
Route::multilingual('/blog-category', [BlogController::class, 'category'])->name('blog.category');
Route::multilingual('/blog-list', [BlogController::class, 'list'])->name('blog.list');

Route::multilingual('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/cache', function () {

    abort_if(request()->get('token', null) !== config('frontstore.api_key'), 404);

    \Illuminate\Support\Facades\Cache::flush();
    return redirect()->route(locale() . '.homepage');
});

//Auth & customer block

Route::group(['middleware' => ['auth.custom']], function () {
    Route::multilingual('/customer-profile', function() {
        return redirect()->route(locale() . '.orders.index');
    })->name('customer.profile');
    Route::multilingual('/customer-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::multilingual('/customer-order', [OrderController::class, 'show'])->name('orders.show');
    Route::multilingual('/logout', [AuthController::class, 'logout'])->name('logout')->method('post');
});

Route::multilingual('/login', [AuthController::class, 'loginForm'])->name('login');
Route::multilingual('/login', [AuthController::class, 'login'])->name('login.post')->method('post');

Route::multilingual('/register', [AuthController::class, 'registerForm'])->name('register');
Route::multilingual('/register', [AuthController::class, 'register'])->name('register.post')->method('post');

Route::multilingual('/forgotten-password', [AuthController::class, 'forgottenPasswordForm'])->name('forgotten_password');
Route::multilingual('/forgotten-password', [AuthController::class, 'requestPasswordReset'])->name('forgotten_password.post')->method('post');

Route::multilingual('/reset-password', [AuthController::class, 'passwordResetForm'])->name('password.reset.form');
Route::multilingual('/reset-password', [AuthController::class, 'passwordReset'])->name('request.password.reset')->method('post');

//END Auth & customer block

Route::multilingual('/home', [PageController::class, 'homepage'])->name('homepage');

Route::multilingual('/', [PageController::class, 'homepage'])->name('homepage');

Route::multilingual('/page', [PageController::class, 'page'])->name('page');

Route::post('/reset-cache', [PageController::class, 'resetCache']);

Route::post("/newsletter", [PageController::class, 'newsletter'])->name('newsletter.store');

Route::get("/xml/facebook", [PageController::class, 'facebookXML'])->name('facebook.xml');

Route::post('/cart/add-variant', [CartController::class, 'addVariant']);
Route::delete('/cart/delete-variant', [CartController::class, 'deleteVariant']);
Route::delete('/cart/remove-variant', [CartController::class, 'removeVariant']);