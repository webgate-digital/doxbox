<!doctype html>
<html lang="{{ locale() }}">

<head>
    <link rel="preconnect" href="https://connect.facebook.net">
    <link rel="preconnect" href="https://www.facebook.com">
    <link rel="preconnect" href="https://www.google-analytics.com">
    <link rel="preconnect" href="https://www.google.com">
    <link rel="preconnect" href="https://www.googleadservices.com">
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @include('googletagmanager::head')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $ogTitle ?? $translations['og.title']['text'] }} | {{ config('app.name') }}</title>
    <meta property="og:title" content="{{ $ogTitle ?? $translations['og.title']['text'] }} | {{ config('app.name') }}">
    <meta name="description" content="{{ $ogDescription ?? $translations['og.description']['text'] }}">
    <meta property="og:description" content="{{ $ogDescription ?? $translations['og.description']['text'] }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('/images/og.png') }}">
    <meta name="robots"
        content="{{ isset($robots) && config('app.env') === 'production' ? $robots : 'noindex,nofollow' }}">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="{{ $ogDescription ?? $translations['og.description']['text'] }}" />
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:url" content="{{ url()->current() }}">

    @yield('json-ld')

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

    @yield('css')

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('images/favicon/safari-pinned-tab.svg" color="#000000') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

    <!-- Facebook Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '855881804786331');
    fbq('track', 'PageView');
  </script>
  <noscript>
    <img height="1" width="1" style="display:none" 
         src="https://www.facebook.com/tr?id=855881804786331&ev=PageView&noscript=1"/>
  </noscript>
  <!-- End Facebook Pixel Code -->
</head>

<body>

    @include('googletagmanager::body')

    <img src="{{ asset('/images/icons/sync_white_24dp.svg') }}" class="hidden" alt="{{ config('app.name') }}">
    <img src="{{ asset('/images/icons/sync_black_24dp.svg') }}" class="hidden" alt="{{ config('app.name') }}">
    <img src="{{ asset('/images/icons/warning_white_24dp.svg') }}" class="hidden" alt="{{ config('app.name') }}">

    <div id="app">

        <div class="top-nav">
            <div class="container">
                <div class="flex items-center justify-between">
                    @if (!str_contains(Route::currentRouteName(), 'cart'))
                        <ul class="top-nav--menu">
                            <li>
                                <a href="{{ route(locale() . '.contact') }}">
                                    {{ $translations['menu.contact']['text'] }}
                                </a>
                            </li>
                            @foreach ($headerPages as $page)
                                <li>
                                    <a href="{{ route(locale() . '.page', [$page['slug']]) }}">
                                        {{ $page['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="flex-grow">
                        <a href="mailto:{{ $catalogSettings['email']['value'] }}" class="top-nav--item">
                            <img class="top-nav--icon" src="{{ asset('images/icons/email_white_24dp.svg') }}"
                                alt="{{ $catalogSettings['email']['value'] }}" width="16">
                            {{ $catalogSettings['email']['value'] }}
                        </a>
                        <a href="tel:{{ $catalogSettings['phone']['value'] }}" class="top-nav--item mr-10">
                            <img class="top-nav--icon" src="{{ asset('images/icons/call_white_24dp.svg') }}"
                                alt="{{ $catalogSettings['phone']['value'] }}" width="16">
                            {{ $catalogSettings['phone']['value'] }}
                        </a>
                        @foreach (config('locales.supported') as $locale)
                            <a href="{{ route($locale . '.homepage') }}" class="top-nav--item"><img
                                    src="{{ asset('images/flags/' . $locale . '.svg') }}" class="top-nav--flag"
                                    width="22" alt="{{ $locale }}"></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <nav class="main-nav">
            <div class="container">
                <div class="flex items-center justify-between">
                    @if (!str_contains(Route::currentRouteName(), 'cart'))
                        <button type="button" class="lg:hidden"
                            onclick="document.getElementById('main-nav--mobile').classList.toggle('is-open');">
                            <img src="{{ asset('images/icons/menu_white_24dp.svg') }}"
                                alt="{{ $translations['cart.title']['text'] }}">
                        </button>
                    @endif
                    
                    <a href="{{ route(locale() . '.homepage') }}" class="main-nav--logo">
                        <img class="hidden md:inline" src="{{ asset('images/logo.svg') }}" width="120"
                            alt="{{ config('app.name') }}">
                        <img class="inline md:hidden" src="{{ asset('images/logo-small.svg') }}" width="36"
                            alt="{{ config('app.name') }}">
                    </a>
                    @if (!str_contains(Route::currentRouteName(), 'cart'))
                        <div class="hidden lg:block">
                            <v-navigation :items="{{ json_encode($categories) }}"/>
                        </div>
                        <div class="flex items-center justify-end relative gap-8">
                            <button type="button"
                                onclick="document.getElementById('search-wrapper').classList.add('opened');">
                                <img src="{{ asset('images/icons/search_white_24dp.svg') }}"
                                    alt="{{ $translations['search.title']['text'] }}">
                            </button>
                            <a href="{{route(locale() . '.customer.profile')}}" class="flex items-center">
                                <img src="{{ asset('images/icons/account-circle.svg') }}">
                            </a>
                            <a href="{{ route(locale() . '.cart') }}" class="flex items-center">
                                <img src="{{ asset('images/icons/shopping_cart_white_24dp.svg') }}"
                                    alt="{{ $translations['cart.title']['text'] }}">
                                <div class="text-small text-white">
                                    <cart-icon></cart-icon>
                                </div>
                            </a>
                            <ul class="main-nav--mobile" id="main-nav--mobile">
                                <v-mobile-navigation :items="{{ json_encode($categories) }}">
                                    <ul>
                                        <li>
                                            <a href="{{ route(locale() . '.contact') }}">
                                                {{ $translations['menu.contact']['text'] }}
                                            </a>
                                        </li>
                                        @foreach ($headerPages as $page)
                                            <li>
                                                <a href="{{ route(locale() . '.page', [$page['slug']]) }}">
                                                    {{ $page['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mobile-navigation__footer__contact">
                                        <a href="mailto:{{ $catalogSettings['email']['value'] }}" class="top-nav--item">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16.666 3.33337H3.33268C2.41221 3.33337 1.66602 4.07957 1.66602 5.00004V15C1.66602 15.9205 2.41221 16.6667 3.33268 16.6667H16.666C17.5865 16.6667 18.3327 15.9205 18.3327 15V5.00004C18.3327 4.07957 17.5865 3.33337 16.666 3.33337ZM16.666 9.16671L11.3743 12.875C10.5486 13.4523 9.45014 13.4523 8.62435 12.875L3.33268 9.16671V7.41671L9.45768 11.7084C9.78341 11.9344 10.2153 11.9344 10.541 11.7084L16.666 7.41671V9.16671Z" fill="#131416"/>
                                            </svg>
                                            {{ $catalogSettings['email']['value'] }}
                                        </a>
                                        <a href="tel:{{ $catalogSettings['phone']['value'] }}" class="top-nav--item mr-10">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.8333 3.74999V2.91666C10.8314 2.80116 10.8765 2.68984 10.9583 2.60832C11.0497 2.52778 11.1704 2.48858 11.2917 2.49999C14.6241 2.7222 17.2778 5.37593 17.5 8.70832C17.5068 8.824 17.4679 8.93773 17.3917 9.02499C17.3101 9.10683 17.1988 9.15195 17.0833 9.14999H16.25C16.0323 9.15069 15.8507 8.98367 15.8333 8.76666C15.6435 6.30908 13.6909 4.35652 11.2333 4.16666C11.0097 4.15771 10.8332 3.97375 10.8333 3.74999ZM10.975 5.94999C10.884 6.02993 10.8323 6.14553 10.8333 6.26666V7.09999C10.8299 7.29919 10.9708 7.47176 11.1667 7.50832C11.8367 7.63098 12.3647 8.14905 12.5 8.81666C12.5366 9.0125 12.7091 9.15337 12.9083 9.14999H13.7417C13.8628 9.15104 13.9784 9.09933 14.0583 9.00832C14.1368 8.91607 14.173 8.79519 14.1583 8.67499C13.9407 7.20819 12.7912 6.05526 11.325 5.83332C11.2024 5.82312 11.0812 5.86553 10.9917 5.94999H10.975ZM14.725 17.5C15.1692 17.5003 15.5952 17.3234 15.9083 17.0083L17.3167 15.6C17.4545 15.4632 17.5209 15.2702 17.4965 15.0775C17.472 14.8849 17.3593 14.7146 17.1917 14.6167L13.975 12.7417C13.6476 12.552 13.2335 12.6068 12.9667 12.875L11.3917 14.45C11.2623 14.5808 11.0625 14.6113 10.9 14.525L10.35 14.225C8.40772 13.181 6.8142 11.5904 5.76667 9.64999L5.475 9.09999C5.38872 8.93751 5.4192 8.73768 5.55 8.60832L7.125 7.03332C7.39319 6.76645 7.44794 6.3524 7.25833 6.02499L5.38333 2.80832C5.2854 2.64065 5.11509 2.52802 4.92247 2.50354C4.72984 2.47905 4.53677 2.54549 4.4 2.68332L2.99167 4.09166C2.67663 4.40482 2.49965 4.83079 2.5 5.27499V5.83332C2.5012 7.21144 2.84178 8.56806 3.49167 9.78332L3.7 10.1583C5.09308 12.7494 7.21729 14.8736 9.80833 16.2667L10.1833 16.475C11.4055 17.1418 12.7744 17.4941 14.1667 17.5H14.725Z" fill="#131416"/>
                                            </svg>
                                            {{ $catalogSettings['phone']['value'] }}
                                        </a>
                                    </div>
                                    <div class="mobile-navigation__footer__flags">
                                        @foreach (config('locales.supported') as $locale)
                                            <a href="{{ route($locale . '.homepage') }}" class="top-nav--item"><img
                                                    src="{{ asset('images/flags/' . $locale . '.svg') }}" class="top-nav--flag"
                                                    width="36" alt="{{ $locale }}"></a>
                                        @endforeach
                                    </div>
                                </v-mobile-navigation>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        @isset($pageContent['flash-news'])
            {!! $pageContent['flash-news']['_editable'] !!}
            <div class="flash-news"
                @if ($pageContent['flash-news']['block']['background_color']['color']) style="background-color: {{ $pageContent['flash-news']['block']['background_color']['color'] }}" @endif>
                <div class="container">
                    <span
                        @if ($pageContent['flash-news']['block']['text_color']['color']) style="color: {{ $pageContent['flash-news']['block']['text_color']['color'] }}" @endif>{{ $pageContent['flash-news']['block']['text'] }}</span>
                </div>
            </div>
        @endisset

        <div class="content">
            @yield('content')
        </div>

        <footer class="footer">
            @if (!str_contains(Route::currentRouteName(), 'cart'))
                <div class="container py-16 text-left">
                    <div class="flex flex-wrap">
                        <div class="w-full lg:w-1/4">
                            <h3 class="text-subheading-l">{{ $translations['footer.social.title']['text'] }}</h3>
                            <div class="flex gap-4 mt-4 lg:mt-10">
                                <a href="{{ $translations['footer.social.instagram.url']['text'] }}" target="_blank">
                                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M32 6H16C10.4772 6 6 10.4772 6 16V32C6 37.5228 10.4772 42 16 42H32C37.5228 42 42 37.5228 42 32V16C42 10.4772 37.5228 6 32 6ZM38.5 32C38.489 35.5853 35.5853 38.489 32 38.5H16C12.4147 38.489 9.51098 35.5853 9.5 32V16C9.51098 12.4147 12.4147 9.51098 16 9.5H32C35.5853 9.51098 38.489 12.4147 38.5 16V32ZM35.5 14.5C35.5 15.6046 34.6046 16.5 33.5 16.5C32.3954 16.5 31.5 15.6046 31.5 14.5C31.5 13.3954 32.3954 12.5 33.5 12.5C34.6046 12.5 35.5 13.3954 35.5 14.5ZM24 15C19.0294 15 15 19.0294 15 24C15 28.9706 19.0294 33 24 33C28.9706 33 33 28.9706 33 24C33.0053 21.6114 32.0588 19.3191 30.3698 17.6302C28.6809 15.9412 26.3886 14.9947 24 15ZM24 29.5C20.9624 29.5 18.5 27.0376 18.5 24C18.5 20.9624 20.9624 18.5 24 18.5C27.0376 18.5 29.5 20.9624 29.5 24C29.5 27.0376 27.0376 29.5 24 29.5Z"
                                            fill="white" />
                                    </svg>
                                </a>
                                <a href="{{ $translations['footer.social.facebook.url']['text'] }}" target="_blank">
                                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M33 12H27C25.8954 12 25 12.8954 25 14V20H33C33.2275 19.995 33.4432 20.1008 33.5784 20.2838C33.7137 20.4667 33.7516 20.704 33.68 20.92L32.2 25.32C32.0636 25.7238 31.6862 25.9968 31.26 26H25V41C25 41.5523 24.5523 42 24 42H19C18.4477 42 18 41.5523 18 41V26H15C14.4477 26 14 25.5523 14 25V21C14 20.4477 14.4477 20 15 20H18V14C18 9.58172 21.5817 6 26 6H33C33.5523 6 34 6.44772 34 7V11C34 11.5523 33.5523 12 33 12Z"
                                            fill="white" />
                                    </svg>
                                </a>
                                <a href="{{ $translations['footer.social.youtube.url']['text'] }}" target="_blank">
                                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M40 8.97985L37.08 8.67985C28.3431 7.77989 19.5369 7.77989 10.8 8.67985L8 8.97985C4.54304 9.37057 1.94711 12.3213 2 15.7998V32.1998C1.94711 35.6784 4.54304 38.6291 8 39.0198L10.92 39.3198C19.6569 40.2198 28.4631 40.2198 37.2 39.3198L40 39.0198C43.457 38.6291 46.0529 35.6784 46 32.1998V15.7998C46.0529 12.3213 43.457 9.37057 40 8.97985ZM30 25.2398L22.22 30.4398C21.767 30.6886 21.2178 30.6865 20.7667 30.4345C20.3155 30.1825 20.0258 29.716 20 29.1998V18.7998C20.001 18.2454 20.3078 17.7368 20.7976 17.4772C21.2875 17.2176 21.8807 17.2494 22.34 17.5598L30.12 22.7598C30.5392 23.033 30.792 23.4995 30.792 23.9998C30.792 24.5002 30.5392 24.9667 30.12 25.2398H30Z"
                                            fill="white" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4 mt-8 lg:mt-0">
                            <h3 class="text-subheading-l">{{ $translations['footer.pages.title']['text'] }}</h3>
                            <ul class="mt-4 lg:mt-10">
                                <li>
                                    <a href="{{ route(locale() . '.contact') }}">
                                        {{ $translations['menu.contact']['text'] }}
                                    </a>
                                </li>
                                @foreach ($footerPages as $page)
                                    <li>
                                        <a href="{{ route(locale() . '.page', [$page['slug']]) }}">
                                            {{ $page['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="w-full lg:w-1/4 mt-8 lg:mt-0">
                            <h3 class="text-subheading-l">{{ $translations['footer.categories.title']['text'] }}</h3>
                            <ul class="mt-4 lg:mt-10">
                                @foreach ($headerNavigationItems as $item)
                                    <li>
                                        <a href="{{ route(locale() . '.product.category', [$item['slug']]) }}">
                                            {{ $item['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="w-full lg:w-1/4 mt-8 lg:mt-0">
                            <h3 class="text-subheading-l">{{ $translations['footer.info.title']['text'] }}</h3>
                            <p class="my-4 lg:my-10">
                                Kazanská 20<br>
                                Podunajské Biskupice<br>
                                82106, Bratislava<br>
                                <a class='text-primary'
                                    href="{{ $translations['footer.info.google_maps.url']['text'] }}"
                                    target="_blank">
                                    {{ $translations['footer.info.google_maps.text']['text'] }}
                                </a>
                            </p>
                            <img src="{{ asset('images/store.png') }}"
                                alt="{{ $supplierSettings['name']['value'] }}" class="w-full md:mt-4 mt-8">
                        </div>
                    </div>
                </div>
            @endif
            <div class="footer-bottom">
                <div class="container">
                    <span class="text-body-xs">{{ date('Y') }} &copy; {{ config('app.name') }}</span>
                </div>
            </div>
        </footer>

        <mini-cart product-url="{{ route(locale() . '.product.detail', ['CATEGORY', 'SLUG']) }}"
            proceed-to-cart-url="{{ route(locale() . '.cart') }}"
            :translations="{{ json_encode(['Váš košík' => $translations['cart.title']['text'], 'Zatvoriť' => $translations['general.cta_close']['text'], 'Na sklade nie je dostatočný počet kusov.' => $translations['cart.count_error']['text'], 'ks' => $translations['ks']['text'], 'Celkom' => $translations['cart.total']['text'], 'Pokračovať v nákupe' => $translations['cart.cta_continue']['text'], 'Zobraziť košík' => $translations['cart.cta_show']['text']]) }}">
        </mini-cart>

    </div>

    <div id="search-wrapper">
        <button type="button" class="absolute right-16 top-16"
            onclick="document.getElementById('search-wrapper').classList.remove('opened');"><img
                src="{{ asset('images/icons/close_black_24dp.svg') }}" width="50"
                alt="{{ $translations['search.title']['text'] }}"></button>
        <div class="container">
            <p class="h1">{{ $translations['search.title']['text'] }}</p>
            <form action="{{ route(locale() . '.search') }}" method="get" class="flex items-stretch">
                <input class="form--input" type="search" name="kw"
                    placeholder="{{ $translations['search.placeholder']['text'] }}">
                <button type="submit" class="button button--primary button--inline">
                    <img src="{{ asset('images/icons/search_white_24dp.svg') }}"
                        alt="{{ $translations['general.cta_close']['text'] }}">
                </button>
            </form>
        </div>
    </div>

    @include('cookie-consent::index')

    @yield('js')

    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        tippy('[data-tippy-content]');
    </script>

</body>

</html>
