@extends('layout', ['robots' => 'index,follow'])

@section('content')
    <main>
        @if($translations['notification.show']['text'] === 'true')
            <a class="py-5 px-4 text-center w-full block text-white text-subheading-s" href="{{$translations['notification.link']['text']}}" style="background-color: {{ $translations['notification.color']['text'] }}">
                {{$translations['notification.text']['text']}}
            </a>
        @endif

        <section class="big-banner" style="--bg-image: url({{asset('images/slide-1.webp')}})">
            <div class="container">
                <h1 class="text-heading-xl mb-10">
                    {!! $translations['slider.sale']['text'] !!}
                </h1>
                <a href="{{route(locale() . '.product.list')}}" class="button button--primary rounded-lg button--inline">
                    {!! $translations['slider.go_to_shop']['text'] !!}
                </a>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="text-heading-2xs md:mb-20 mb-6">
                    {{$translations['products.top_selling_title']['text']}}
                </h2>
                <div class="product-container product-container--homepage">
                    @foreach($products as $product)
                        @include('components.product', ['item' => $product])
                    @endforeach
                </div>
                <div class="text-center mt-10 md:mt-20">
                    <a href="{{route(locale() . '.product.list')}}" class="button border border-primary text-primary rounded-lg button--inline">
                        {{$translations['products.show_all_cta']['text']}}
                    </a>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="text-heading-2xs md:mb-20 mb-6">
                    {{$translations['categories.title']['text']}}
                </h2>
                <div class="category-container">
                    @foreach(array_slice($categories, 0, 12) as $category)
                        @include('components.category', ['item' => $category])
                    @endforeach
                </div>
            </div>
        </section>

        <div class="promo-section promo-section--big bg-gray-5" style="--bg-image: url(/images/promo-1.webp)">
            <div>
                <h2 class="text-heading-xs leading-none">
                    {!!$translations['homepage.product.1.title']['text']!!}
                </h2>
                <p class="text-body-xl">
                    {!!$translations['homepage.product.1.description']['text']!!}
                </p>
                <a href="{{$translations['homepage.product.1.link']['text']}}" class="button button--primary button--inline rounded-lg">
                    {!!$translations['homepage.product.1.cta']['text']!!}
                </a>
            </div>
        </div>
        
        <div class="promo-section">
            <div class="bg-gray-5" style="--bg-image: url(/images/promo-2.webp)">
                <h2 class="text-heading-xs leading-none">
                    {!!$translations['homepage.product.2.title']['text']!!}
                </h2>
                <p class="text-body-xl">
                    {!!$translations['homepage.product.2.description']['text']!!}
                </p>
                <a href="{{$translations['homepage.product.2.link']['text']}}" class="button button--primary button--inline rounded-lg">
                    {!!$translations['homepage.product.2.cta']['text']!!}
                </a>
            </div>
            <div class="bg-secondary text-white" style="--bg-image: url(/images/promo-3.webp)">
                <h2 class="text-heading-xs leading-none">
                    {!!$translations['homepage.product.3.title']['text']!!}
                </h2>
                <p class="text-body-xl">
                    {!!$translations['homepage.product.3.description']['text']!!}
                </p>
                <a href="{{$translations['homepage.product.3.link']['text']}}" class="button button--primary button--inline rounded-lg">
                    {!!$translations['homepage.product.3.cta']['text']!!}
                </a>
            </div>
            
            <div class="bg-secondary text-white" style="--bg-image: url(/images/promo-4.webp)">
                <h2 class="text-heading-xs leading-none">
                    {!!$translations['homepage.product.4.title']['text']!!}
                </h2>
                <p class="text-body-xl">
                    {!!$translations['homepage.product.4.description']['text']!!}
                </p>
                <a href="{{$translations['homepage.product.4.link']['text']}}" class="button button--primary button--inline rounded-lg">
                    {!!$translations['homepage.product.4.cta']['text']!!}
                </a>
            </div>

            <div class="bg-gray-5" style="--bg-image: url(/images/promo-5.webp)">
                <h2 class="text-heading-xs leading-none">
                    {!!$translations['homepage.product.5.title']['text']!!}
                </h2>
                <p class="text-body-xl">
                    {!!$translations['homepage.product.5.description']['text']!!}
                </p>
                <a href="{{$translations['homepage.product.5.link']['text']}}" class="button button--primary button--inline rounded-lg">
                    {!!$translations['homepage.product.5.cta']['text']!!}
                </a>
            </div>
        </div>

        <div class="promo-section promo-section--big bg-secondary text-white mb-32" style="--bg-image: url(/images/promo-6.webp)">
            <div>
                <h2 class="text-heading-xs leading-none">
                    {!!$translations['homepage.product.6.title']['text']!!}
                </h2>
                <p class="text-body-xl">
                    {!!$translations['homepage.product.6.description']['text']!!}
                </p>
                <a href="{{$translations['homepage.product.6.link']['text']}}" class="button button--primary button--inline rounded-lg">
                    {!!$translations['homepage.product.6.cta']['text']!!}
                </a>
            </div>
        </div>

        @include('components.newsletter-section')
    </main>
@endsection
