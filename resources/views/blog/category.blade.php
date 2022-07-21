@extends('layout')

@section('content')
    <!-- Breadrumbs -->
    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
            'url' => route(locale() . '.blog.list'),
            'title' => $translations['blog.title']['text']
        ];
        $breadcrumbs[] = [
            'url' => url()->current(),
            'title' => $category['name']
        ];
    @endphp
    @include('components.breadcrumbs')
    <!-- end: Breadrumbs -->

    <!-- Blog articles -->
    <section class="section">
        <div class="container">
            <div class="reading-content">
                <h1 class="h1">
                    {{$category['name']}}
                </h1>
                <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-12">
                    @foreach($category['articles'] as $item)
                        <div>
                            @include('components.blog_article', ['item' => $item])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- end: Blog articles -->
@endsection
