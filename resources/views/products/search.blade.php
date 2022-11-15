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
            <h1 class="text-heading-2xs mb-8">
                {{$translations['search.title']['text']}}: {{request()->get('kw')}}
            </h1>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                    @include('components.product', ['item' => $product])
                @endforeach
            </div>
        </div>
    </section>

    @include('components.newsletter-section')

@endsection
