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
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '855881804786331');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=855881804786331&ev=PageView&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->
</head>

<body>
    @include('googletagmanager::body')
    <div class="container">
        <div class="flex items-center justify-center flex-col" style="min-height:100vh;">
            <img src="{{ asset('images/logo-black.svg') }}" alt="Logo" class="logo">
            <div class="my-16 flex flex-col gap-4">
                <h1 class="text-center text-heading-xs">
                    Náš eshop má práve problém
                </h1>
                <h2 class="text-center text-subheading-xl" style="max-width:480px;margin: 0 auto">
                    Na riešení pracujeme. Skúste prosím neskôr, alebo nás kontaktujte:
                </h2>
            </div>
            <div>
                <h3 class="text-center text-subheading-m">Zákaznícky servis</h3>
                <p class="text-body-m">
                    Email:
                    <a class="underline inline-block" href="mailto:{{ $catalogSettings['email']['value'] }}">
                        {{ $catalogSettings['email']['value'] }}
                    </a>
                    <br />
                    Tel:
                    <a class="underline inline-block" href="tel:{{ $catalogSettings['phone']['value'] }}">
                        {{ $catalogSettings['phone']['value'] }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
