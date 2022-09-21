<?php

namespace App\Providers;

use App\Repositories\PageRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SettingRepository;
use App\Repositories\SetupRepository;
use App\Repositories\TranslationRepository;
use Cache;
use Illuminate\Support\ServiceProvider;
use GoogleTagManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (request()->cookie('the_cookies_analytics') === '0') {
            GoogleTagManager::disable();
        }

        view()->composer('*', function ($view) {
            $catalogSettings = Cache::rememberForever('catalog_settings', function () {
                $_settingRepository = new SettingRepository();
                return $_settingRepository->catalog()['items'];
            });

            $documentSettings = Cache::rememberForever('document_settings', function () {
                $_settingRepository = new SettingRepository();
                return $_settingRepository->documents()['items'];
            });

            $supplierSettings = Cache::rememberForever('supplier_settings', function () {
                $_settingRepository = new SettingRepository();
                return $_settingRepository->supplier()['items'];
            });

            $translations = Cache::rememberForever('translations_web', function () {
                $_translationRepository = new TranslationRepository();
                return $_translationRepository->default(locale())['items'];
            });

            $headerPages = Cache::rememberForever('pages_header', function () {
                $_pageRepository = new PageRepository();
                return $_pageRepository->list(locale(), session()->get('currency'), 0, 0, 'desc', 'score', true, false, false, false)['items'];
            });

            $footerPages = Cache::rememberForever('pages_footer', function () {
                $_pageRepository = new PageRepository();
                return $_pageRepository->list(locale(), session()->get('currency'), 0, 0, 'desc', 'score', false, true, false, false)['items'];
            });

            $categories = Cache::rememberForever('header_navigation_items', function () {
                $_productRepository = new ProductRepository();
                return $_productRepository->categories(locale(), 0, 0, 'desc', 'score')['items'];
            });

            $headerNavigationItems = array_filter($categories, function($item) {
                return $item['has_parent'] === false;
            });

            // Add url to categories
            foreach ($categories as $key => $category) {
                $categories[$key]['url'] = route(locale() . '.product.category', [$category['slug']]);
            }

            $setup = Cache::rememberForever('setup', function () {
                $_setupRepository = new SetupRepository();
                return $_setupRepository->list()['items'];
            });

            $gdprPage = Cache::rememberForever('gdpr_page', function () {
                $_pageRepository = new PageRepository();
                $list = $_pageRepository->list(locale(), session()->get('currency'), 0, 0, 'desc', 'score', false, false, false, true)['items'];
                if (isset($list[0])) {
                    return $list[0];
                }

                return [
                    'slug' => 'gdpr',
                ];
            });

            $view->with('catalogSettings', $catalogSettings);
            $view->with('translations', $translations);
            $view->with('headerPages', $headerPages);
            $view->with('footerPages', $footerPages);
            $view->with('categories', $categories);
            $view->with('headerNavigationItems', $headerNavigationItems);
            $view->with('supplierSettings', $supplierSettings);
            $view->with('setup', $setup);
            $view->with('documentSettings', $documentSettings);
            $view->with('gdprPage', $gdprPage);
        });
    }
}
