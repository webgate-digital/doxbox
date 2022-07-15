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
                        <div class="w-full lg:w-1/3 lg:px-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-36 w-36 my-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="h3">Výhoda 1</h3>
                            <p>Sed at turpis maximus, mattis mauris et, sagittis dui. Nullam lobortis enim sapien, pretium elementum libero ornare id. Donec elit leo, placerat vitae ligula quis, pulvinar blandit elit. In elementum.</p>
                        </div>
                        <div class="w-full lg:w-1/3 lg:px-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-36 w-36 my-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="h3">Výhoda 2</h3>
                            <p>Morbi fermentum dolor ac cursus dignissim. Aenean et erat ac metus ornare malesuada ac in diam. Suspendisse lacinia ultricies velit, vitae luctus sapien efficitur vitae. Curabitur a euismod dui, sed.</p>
                        </div>
                        <div class="w-full lg:w-1/3 lg:px-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-36 w-36 my-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                            <h3 class="h3">Výhoda 3</h3>
                            <p>Nam ornare rhoncus imperdiet. Mauris semper sapien vitae neque tincidunt interdum. Sed volutpat faucibus ornare. Etiam id mattis purus. Suspendisse tempus risus vel leo suscipit, vitae cursus erat lobortis. Mauris.</p>
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
