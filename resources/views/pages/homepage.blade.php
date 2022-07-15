@extends('layout', ['robots' => 'index,follow'])

@section('content')
    <main>
        <section class="big-banner" style="background-image: url({{asset('images/slide-1.jpeg')}})">
            <div class="container">
                <div class="flex flex-wrap items-center">
                    <div class="w-full lg:w-1/2">
                        <p class="h2 leading-none">Využite jedinečný</p>
                        <h1 class="h1 !text-[160px] leading-none text-secondary">
                            <p>Výpredaj</p>
                        </h1>
                        <a href="https://frontstore.theshop.sk"
                           class="button button--primary button--inline">Prejsť do obchodu</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <h2 class="h2 text-center">{{$translations['products.top_selling_title']['text']}}</h2>
                <div class="product-container">
                    @foreach($products as $product)
                        <div class="w-1/2 lg:w-1/4">
                            @include('components.product', ['item' => $product])
                        </div>
                    @endforeach
                </div>
                <div class="text-center">
                    <a href="{{route(locale() . '.product.list')}}"
                       class="button--secondary button button--lg button--inline mt-8">{{$translations['products.show_all_cta']['text']}}</a>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="text-center">
                    <div class="flex flex-wrap">
                        <div class="w-full lg:w-1/3">
                            <img src="{{asset('images/logo-theshop.svg')}}" class="mb-8 mx-auto max-w-[150px]"
                                 alt="theshop">
                            <h3 class="h3">Výhoda 1</h3>
                            <p>Text výhody 1</p>
                        </div>
                        <div class="w-full lg:w-1/3">
                            <img src="{{asset('images/logo-theshop.svg')}}" class="mb-8 mx-auto max-w-[150px]"
                                 alt="theshop">
                            <h3 class="h3">Výhoda 2</h3>
                            <p>Text výhody 2</p>
                        </div>
                        <div class="w-full lg:w-1/3">
                            <img src="{{asset('images/logo-theshop.svg')}}" class="mb-8 mx-auto max-w-[150px]"
                                 alt="theshop">
                            <h3 class="h3">Výhoda 3</h3>
                            <p>Text výhody 3</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <h2 class="h2 text-center">
                    {{$translations['categories.title']['text']}}
                </h2>
                <div class="category-container">
                    @foreach($categories as $category)
                        <div class="w-1/2 lg:w-1/4">
                            @include('components.category', ['item' => $category])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
{{--        <section class="section bg-primary">--}}
{{--            <div class="container">--}}
{{--                <h2 class="h2 text-center">--}}
{{--                    {{$translations['newsletter.title']['text']}}--}}
{{--                </h2>--}}
{{--                <p class="text-center">{{$translations['newsletter.text']['text']}}</p>--}}
{{--            </div>--}}
{{--        </section>--}}
    </main>
@endsection
