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
    <section class="section">
        <div class="container">
            <div class="flex flex-wrap items-center -mx-4">
                <div class="w-full lg:w-1/3 px-4">
                    <h1 class="h1 @if(!$category['description']) !mb-0 @endif lg:mb-0">
                        {{$translations['sort.' . request()->get('sort', $setup['api']['defaults']['sort']['products'])]['text'] . ' ' . \Illuminate\Support\Str::lower($category['name'])}}
                    </h1>
                </div>
                <div class="w-full lg:w-2/3 px-4">
                    <p class="lg:mb-0">{{$category['description']}}</p>
                </div>
            </div>
        </div>
    </section>

    @include('components.category_list')

    <section class="section" id="list">
        <div class="container">
            <div class="flex flex-wrap-reverse lg:flex-wrap -mx-4">
                <div class="w-full lg:w-1/4 px-4">
                    @include('components.product_sidebar')
                </div>
                <div class="w-full lg:w-3/4 px-4">
                    @include('components.product_list')
                </div>
            </div>
        </div>
    </section>

@endsection
