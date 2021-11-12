@extends('layout', ['robots' => 'index,follow'])

@section('content')
    <main>
        @isset($pageContent['big-banner'])
            {!! $pageContent['big-banner']['_editable'] !!}
            <section class="big-banner">
                <div class="container">
                    <div class="flex flex-wrap items-center">
                        <div class="w-full lg:w-1/2">
                            @if($pageContent['big-banner']['block']['subheadline'])
                                <p class="h2">{{$pageContent['big-banner']['block']['subheadline']}}</p>
                            @endif
                            <h1 class="h1">
                                {{$pageContent['big-banner']['block']['headline']}}
                            </h1>
                            <a href="{{$pageContent['big-banner']['block']['cta_url']}}"
                               class="button button--primary button--inline">{{$pageContent['big-banner']['block']['cta_text']}}</a>
                        </div>
                        <div class="w-full lg:w-1/2">
                            <img src="{{$pageContent['big-banner']['block']['image']['filename']}}"
                                 alt="{{$pageContent['big-banner']['block']['image']['alt']}}" class="mt-16 lg:mt-0 mx-auto">
                        </div>
                    </div>
                </div>
            </section>
        @endisset
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
        @isset($pageContent['icon-boxes'])
            <section class="section">
                <div class="container">
                    <div class="text-center">
                        <div class="flex flex-wrap">
                            {!! $pageContent['icon-boxes']['_editable'] !!}
                            @foreach($pageContent['icon-boxes']['columns'] as $column)
                                <div class="w-full lg:w-1/3">
                                    <img src="{{$column['image']['filename']}}" class="mb-8 mx-auto max-w-[150px]"
                                         alt="{{$column['image']['alt']}}">
                                    <h3 class="h3">{{$column['title']}}</h3>
                                    <p>{{$column['text']}}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endisset
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

@section('js')
    <script type="text/javascript" src="//app.storyblok.com/storyblok-latest.js"></script>
    <script type="text/javascript">
        storyblok.init();
        storyblok.on('change', function () {
            window.location.reload(true);
        });
    </script>
@endsection
