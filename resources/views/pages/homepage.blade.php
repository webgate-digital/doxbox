@extends('layout', ['robots' => 'index,follow'])

@section('content')
    <main>

        <section class="big-banner" style="--bg-image: url({{asset('images/slide-1.png')}})">
            <div class="container">
                <h1 class="text-heading-xl mb-10">
                    {{$translations['slider.sale']['text']}}
                </h1>
                <a href="https://frontstore.theshop.sk" class="button button--primary rounded-lg button--inline">
                    {{$translations['slider.go_to_shop']['text']}}
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

        <section class="section">
            <div class="container">
                <h2 class="text-heading-2xs md:mb-20 mb-6">
                    Značky {{--  TODO: translate --}}
                </h2>
                <div class="category-container">
                    @foreach($brands as $brand)
                        @include('components.category', ['item' => $brand])
                    @endforeach
                </div>
            </div>
        </section>

        <div class="promo-section promo-section--big bg-gray-5" style="--bg-image: url(/images/promo-1.png)">
            <div>
                <h2 class="text-heading-xs leading-none">Headline</h2>
                <p class="text-body-xl">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
                <a href="#" class="button button--primary button--inline rounded-lg">
                    Lorem ipsum
                </a>
            </div>
        </div>
        
        <div class="promo-section">
            <div class="bg-gray-5" style="--bg-image: url(/images/promo-2.png)">
                <h2 class="text-heading-xs leading-none">Headline</h2>
                <p class="text-body-xl">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
                <a href="#" class="button button--primary button--inline rounded-lg">
                    Lorem ipsum
                </a>
            </div>
            <div class="bg-secondary text-white" style="--bg-image: url(/images/promo-3.png)">
                <h2 class="text-heading-xs leading-none">Headline</h2>
                <p class="text-body-xl">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
                <a href="#" class="button button--primary button--inline rounded-lg">
                    Lorem ipsum
                </a>
            </div>
            
            <div class="bg-secondary text-white" style="--bg-image: url(/images/promo-4.png)">
                <h2 class="text-heading-xs leading-none">Headline</h2>
                <p class="text-body-xl">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
                <a href="#" class="button button--primary button--inline rounded-lg">
                    Lorem ipsum
                </a>
            </div>

            <div class="bg-gray-5" style="--bg-image: url(/images/promo-5.png)">
                <h2 class="text-heading-xs leading-none">Headline</h2>
                <p class="text-body-xl">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
                <a href="#" class="button button--primary button--inline rounded-lg">
                    Lorem ipsum
                </a>
            </div>
        </div>

        <div class="promo-section promo-section--big bg-secondary text-white mb-32">
            <div>
                <h2 class="text-heading-xs leading-none">Headline</h2>
                <p class="text-body-xl">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
                <a href="#" class="button button--primary button--inline rounded-lg">
                    Lorem ipsum
                </a>
            </div>
        </div>

        @include('components.newsletter-section')
    </main>
@endsection
