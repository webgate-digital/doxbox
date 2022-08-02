<!doctype html>
<html lang="{{locale()}}">
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
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{$ogTitle ?? $translations['og.title']['text']}} | {{config('app.name')}}</title>
    <meta property="og:title" content="{{$ogTitle ?? $translations['og.title']['text']}} | {{config('app.name')}}">
    <meta name="description" content="{{$ogDescription ?? $translations['og.description']['text']}}">
    <meta property="og:description" content="{{$ogDescription ?? $translations['og.description']['text']}}">
    <meta property="og:image" content="{{$ogImage ?? asset('/images/og.png')}}">
    <meta name="robots"
          content="{{isset($robots) && config('app.env') === 'production' ? $robots : 'noindex,nofollow'}}">
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:description" content="{{$ogDescription ?? $translations['og.description']['text']}}"/>
    <link rel="canonical" href="{{url()->current()}}">
    <meta property="og:url" content="{{url()->current()}}">

    @yield('json-ld')

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

    @yield('css')

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('images/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('images/favicon/safari-pinned-tab.svg" color="#000000')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
</head>
<body>

@include('googletagmanager::body')

<img src="{{asset('/images/icons/sync_white_24dp.svg')}}" class="hidden" alt="{{config('app.name')}}">
<img src="{{asset('/images/icons/sync_black_24dp.svg')}}" class="hidden" alt="{{config('app.name')}}">
<img src="{{asset('/images/icons/warning_white_24dp.svg')}}" class="hidden" alt="{{config('app.name')}}">

<div id="app">

    @if(!str_contains(Route::currentRouteName(), 'cart.checkout'))
        <div class="top-nav">
            <div class="container">
                <div class="flex items-center justify-between">
                    <div>
                        <ul class="top-nav--menu">
                            <li>
                                <a href="#">Kontakt</a>
                            </li>
                            <li>
                                <a href="#">Doprava</a>
                            </li>
                            <li>
                                <a href="#">Vrátenie tovaru</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <a href="mailto:{{$catalogSettings['email']['value']}}" class="top-nav--item"><img
                                src="{{asset('images/icons/email_white_24dp.svg')}}"
                                alt="{{$catalogSettings['email']['value']}}" width="16"
                                class="top-nav--icon"> {{$catalogSettings['email']['value']}}</a>
                        <a href="tel:{{$catalogSettings['phone']['value']}}" class="top-nav--item hidden lg:inline mr-10"><img
                                src="{{asset('images/icons/call_white_24dp.svg')}}"
                                alt="{{$catalogSettings['phone']['value']}}" width="16"
                                class="top-nav--icon"> {{$catalogSettings['phone']['value']}}</a>
                        @foreach(config('locales.supported') as $locale)
                            <a href="{{route($locale . '.homepage')}}" class="top-nav--item"><img
                                    src="{{asset('images/flags/'.$locale.'.svg')}}" class="top-nav--flag"
                                    width="22"
                                    alt="{{$locale}}"></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <nav class="main-nav">
        <div class="container">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{route(locale() . '.homepage')}}">
                        <img class="inline" src="{{asset('images/logo.svg')}}" width="120" alt="{{config('app.name')}}">
                    </a>
                </div>
                @if(!str_contains(Route::currentRouteName(), 'cart.checkout'))
                    <div>
                        <ul class="main-nav--menu hidden lg:block">
                            @include('components.main_nav')
                        </ul>
                    </div>
                @endif
                @if(!str_contains(Route::currentRouteName(), 'cart.checkout'))
                    <div>
                        <div class="flex items-center justify-end relative">
                            <button type="button"
                                    onclick="document.getElementById('search-wrapper').classList.add('opened');">
                                <img src="{{asset('images/icons/search_white_24dp.svg')}}"
                                     alt="{{$translations['search.title']['text']}}">
                            </button>
                            <a href="{{route(locale() . '.cart')}}" class="ml-8 flex items-center">
                                <img src="{{asset('images/icons/shopping_cart_white_24dp.svg')}}" class="mr-2"
                                     alt="{{$translations['cart.title']['text']}}">
                                <div class="text-small text-white">
                                    <cart-icon></cart-icon>
                                </div>
                            </a>
                            <button type="button"
                                    onclick="document.getElementById('main-nav--mobile').classList.toggle('is-open');">
                                <img src="{{asset('images/icons/menu_white_24dp.svg')}}" class="ml-8 lg:hidden"
                                     alt="{{$translations['cart.title']['text']}}">
                            </button>
                            <ul class="main-nav--mobile" id="main-nav--mobile">
                                @include('components.main_nav')
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    @isset($pageContent['flash-news'])
        {!! $pageContent['flash-news']['_editable'] !!}
        <div class="flash-news"
             @if($pageContent['flash-news']['block']['background_color']['color']) style="background-color: {{$pageContent['flash-news']['block']['background_color']['color']}}" @endif>
            <div class="container">
            <span
                @if($pageContent['flash-news']['block']['text_color']['color']) style="color: {{$pageContent['flash-news']['block']['text_color']['color']}}" @endif>{{$pageContent['flash-news']['block']['text']}}</span>
            </div>
        </div>
    @endisset

    <div class="content">
        @yield('content')
    </div>

    <footer class="footer">
        <div class="container">
            <div class="flex flex-wrap">
                <div class="w-full lg:w-1/4">
                    @if(!str_contains(Route::currentRouteName(), 'cart.checkout'))
                        <p><b>{{$supplierSettings['name']['value']}}</b><br>
                            {{$supplierSettings['address']['value']}}<br>
                            {{$supplierSettings['zip']['value']}} {{$supplierSettings['city']['value']}}<br>
                            {{$translations['general.company_id']['text']}}: {{$supplierSettings['id']['value']}}<br>
                            {{$translations['general.company_tax_id']['text']}}: {{$supplierSettings['tax_id']['value']}}
{{--                            {{$translations['general.company_vat_id']['text']}}--}}
{{--                            : {{$supplierSettings['vat_id']['value']}}--}}
                        </p>
                    @endif
                </div>
                <div class="w-full lg:w-1/4">
                    <p>
                        <b>{{$translations['footer.useful_links_title']['text']}}</b>
                    </p>
                    <ul>
                        @foreach($footerPages as $footerPage)
                            <li>
                                <a href="{{route(locale() . '.page', [$footerPage['slug']])}}">{{$footerPage['title']}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="w-full lg:w-1/4">
                    <p>
                        <b>{{$translations['footer.useful_links_title']['text']}}</b>
                    </p>
                    <ul>
                        @foreach($footerPages as $footerPage)
                            <li>
                                <a href="{{route(locale() . '.page', [$footerPage['slug']])}}">{{$footerPage['title']}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="w-full lg:w-1/4">
                    <p>
                        <b>{{$translations['footer.useful_links_title']['text']}}</b>
                    </p>
                    <ul>
                        @foreach($footerPages as $footerPage)
                            <li>
                                <a href="{{route(locale() . '.page', [$footerPage['slug']])}}">{{$footerPage['title']}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                {{date('Y')}} &copy; {{config('app.name')}}
            </div>
        </div>
    </footer>

    <mini-cart product-url="{{route(locale() . '.product.detail', ['CATEGORY', 'SLUG'])}}"
               proceed-to-cart-url="{{route(locale() . '.cart')}}"
               :translations="{{json_encode(['Váš košík' => $translations['cart.title']['text'], 'Zatvoriť' => $translations['general.cta_close']['text'], 'Na sklade nie je dostatočný počet kusov.' => $translations['cart.count_error']['text'], 'ks' => $translations['ks']['text'], 'Celkom' => $translations['cart.total']['text'], 'Pokračovať v nákupe' => $translations['cart.cta_continue']['text'], 'Zobraziť košík' => $translations['cart.cta_show']['text']])}}"
    ></mini-cart>

</div>

<div id="search-wrapper">
    <button type="button" class="absolute right-16 top-16"
            onclick="document.getElementById('search-wrapper').classList.remove('opened');"><img
            src="{{asset('images/icons/close_black_24dp.svg')}}" width="50"
            alt="{{$translations['search.title']['text']}}"></button>
    <div class="container">
        <p class="h1">{{$translations['search.title']['text']}}</p>
        <form action="{{route(locale().'.search')}}" method="get" class="flex items-stretch">
            <input class="form--input" type="search" name="kw"
                   placeholder="{{$translations['search.placeholder']['text']}}">
            <button type="submit" class="button button--primary button--inline">
                <img src="{{asset('images/icons/search_white_24dp.svg')}}"
                     alt="{{$translations['general.cta_close']['text']}}">
            </button>
        </form>
    </div>
</div>

@yield('js')

<script src="{{mix('js/app.js')}}"></script>

</body>
</html>
