@extends('layout', ['ogTitle' => $item['seo_title'] ?: $item['title'], 'ogDescription' => $item['seo_description'] ?: null])

@section('content')
    <!-- Page title -->
    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
        'url' => url()->current(),
        'title' => $item['title']
        ];
    @endphp
    @include('components.breadcrumbs')
    <!-- end: Page title -->
    <section class="section">
        <div class="container">
            <div class="reading-content">
                <h1 class="h1">
                    {{$item['title']}}
                </h1>
                <div class="wysiwyg-content">
                    {!! $item['content'] !!}
                </div>
            </div>
            @if(count($item['products']))
                <div class="product-container">
                    @foreach($item['products'] as $product)
                        <div class="w-1/2 lg:w-1/4">
                            @include('components.product', ['item' => $product])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

@endsection
