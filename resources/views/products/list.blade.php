@extends('layout', [
	'ogTitle' => $translations['sort.' . request()->get('sort', $setup['api']['defaults']['sort']['products'])]['text'] . ' ' . $translations['filter.products_suffix']['text'],
	'ogDescription' => $translations['products.intro']['text'],
	'robots' => 'index,follow'
])

@section('content')
    <!-- Page title -->
    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
        'url' => url()->current(),
        'title' => $translations['sort.' . request()->get('sort', $setup['api']['defaults']['sort']['products'])]['text'] . ' ' . $translations['filter.products_suffix']['text']
        ];
    @endphp
    @include('components.breadcrumbs')
    <!-- end: Page title -->
    <section class="section bg-gray-5">
        <div class="container">
            <h1 class="h1 lg:mb-0">
                {{$translations['sort.' . request()->get('sort', $setup['api']['defaults']['sort']['products'])]['text'] . ' ' . $translations['filter.products_suffix']['text']}}
            </h1>
            <p class="w-1/2 lg:mb-0">{{$translations['products.intro']['text']}}</p>
        </div>
        
        @include('components.category_list')
    </section>


    <section class="section" id="list">
        <div class="container">
            <div class="flex flex-wrap-reverse lg:flex-wrap -mx-4" style="display: grid; grid-template-columns: 1fr 3fr; grid-gap: 2rem;">
                @include('components.product_sidebar')
                @include('components.product_list')
            </div>
        </div>
    </section>

@endsection
