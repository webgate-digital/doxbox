@extends('layout', [
	'ogTitle' => $translations['sort.' . request()->get('sort', $setup['api']['defaults']['sort']['products'])]['text'] . ' ' . \Illuminate\Support\Str::lower($category['seo_title'] ?: $category['name']),
	'ogDescription' => $category['seo_description'] ?? $category['description'],
	'robots' => 'index,follow'
])

@section('content')
    <!-- Page title -->
    @php
        $breadcrumbs = [];
$breadcrumbs[] = [
        'url' => route(locale().'.product.list'),
        'title' => $translations['menu.products']['text']
        ];
        $breadcrumbs[] = [
        'url' => url()->current(),
        'title' => $category['name']
        ];
    @endphp
    @include('components.breadcrumbs')
    <!-- end: Page title -->
    <section class="section bg-gray-5">
        <div class="container">
            <h1 class="text-heading-2xs @if(!$category['description']) !mb-0 @endif lg:mb-0">
                {{$translations['sort.' . request()->get('sort', $setup['api']['defaults']['sort']['products'])]['text'] . ' ' . \Illuminate\Support\Str::lower($category['name'])}}
            </h1>
            <p class="w-1/2 lg:mb-0">{{$category['description']}}</p>
        </div>

        @include('components.category_list')
    </section>

    <section class="section" id="list">
        <div class="container">
            <div class="flex flex-wrap-reverse lg:flex-wrap -mx-4">
                <div class="w-full lg:w-1/4 px-4">
                    @include('components.product_sidebar')
                </div>
                <div class="w-full lg:w-3/4 px-4">
                    @include('components.product_list')

                    @if($hasMoreProducts)
                        <div class="mt-24 text-center">
                            <button id="load-more" data-page="1" class="button border border-primary text-primary rounded-lg !inline-flex !w-auto items-center">
                                <svg id='load-more--loading' class="animate-spin hidden mr-3 h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>
                                    {{$translations['products.show_more_text']['text']}}
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @include('components.newsletter-section')

@endsection
