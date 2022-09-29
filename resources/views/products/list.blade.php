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
    <section class="p-20 bg-gray-5">
        <div class="container">
            <h1 class="text-heading-2xs lg:mb-0">
                {{$category['name']}}
            </h1>
            <p class="w-1/2 lg:mb-0">{{$translations['products.intro']['text']}}</p>
        </div>
        
        @if($category['children'])
            @include('components.category_list')
        @endif
    </section>


    <section class="section" id="list">
        <div class="container">
            <div class="flex flex-wrap-reverse lg:flex-wrap -mx-4" style="display: grid; grid-template-columns: 1fr 3fr; grid-gap: 2rem;">
                @include('components.product_sidebar')
                @include('components.product_list')
            </div>
        </div>
    </section>

    @include('components.newsletter-section')

@endsection
