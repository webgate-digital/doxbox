@extends('layout', [
	'ogTitle' => $translations['search.title']['text'].': ' . request()->get('kw'),
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
        'title' => $translations['search.title']['text'].': ' . request()->get('kw')
        ];
    @endphp
    @include('components.breadcrumbs')
    <!-- end: Page title -->
    <section class="section">
        <div class="container">
            <div class="flex flex-wrap items-center -mx-4">
                <div class="w-full lg:w-1/3 px-4">
                    <h1 class="text-heading-2xs lg:mb-0">
                        {{$translations['search.title']['text'].': ' . request()->get('kw')}}
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="product-container mb-32 lg:mb-0">
                @foreach($products as $product)
                    <div class="w-1/2 lg:w-1/3">
                        @include('components.product', ['item' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('components.newsletter-section')

@endsection
