@extends('layout', ['robots' => 'index,follow'])

@section('content')
    <main>

        <section class="big-banner" style="--bg-image: url({{asset('images/slide-1.png')}})">
            <h1 class="h1 !text-[96px] mt-80 leading-none">
                {{$translations['slider.sale']['text']}}
            </h1>
            <a href="https://frontstore.theshop.sk" class="button button--primary rounded-lg button--inline">
                {{$translations['slider.go_to_shop']['text']}}
            </a>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="h1 md:mb-20">{{$translations['products.top_selling_title']['text']}}</h2>
                <div class="product-container">
                    @foreach($products as $product)
                        @include('components.product', ['item' => $product])
                    @endforeach
                </div>
                <div class="text-center mt-20">
                    <a href="{{route(locale() . '.product.list')}}" class="button border border-primary text-primary rounded-lg button--inline">
                        {{$translations['products.show_all_cta']['text']}}
                    </a>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="h1">
                    {{$translations['categories.title']['text']}}
                </h2>
                <div class="category-container">
                    @foreach($categories as $category)
                        @include('components.category', ['item' => $category])
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="h1">
                    Značky {{--  TODO: translate --}}
                </h2>
                <div class="category-container">
                    @foreach($brands as $brand)
                        @include('components.category', ['item' => $brand])
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section section--newsletter">
            <div class="container">
                <h2 class="h1 text-center">Odoberaj DOXBOX Newsletter</h2>
                <h3 class="h4 text-center">Informácie o zľavách, nových produktoch  a ďalších novinkách</h3>
                <p class="text-center">Prihlásením sa k odberu súhlasíš s spracovaním osobných údajov na marketingové účely.</p>
            </div>
        </section>
    </main>
@endsection
