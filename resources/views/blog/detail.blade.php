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
            'url' => route(locale() . '.blog.category', [$item['category']['slug']]),
            'title' => $item['category']['name']
        ];
        $breadcrumbs[] = [
            'url' => url()->current(),
            'title' => $item['title']
        ];
    @endphp
    @include('components.breadcrumbs')
    <!-- end: Breadrumbs -->

    <!-- Page Content -->
    <section class="section">
        <div class="container">
            <!-- Blog -->
            <div class="reading-content">
                <!-- Post single item-->
                <div class="post-item">
                    <img alt="{{$item['title']}}" src="{{$item['detail_image_url']}}" class="mb-8">
                    <div class="post-item-description">
                        <h1 class="h1">
                            {{$item['title']}}
                        </h1>
                        <hr class="mb-4">
                        <div class="blog-content">
                            {!! $item['content'] !!}
                        </div>
                    </div>
{{--                                <div class="post-tags">--}}
{{--                                    @foreach($blogArticle->tags as $blogArticleTag)--}}
{{--                                        <a href="{{route('web.blog.tag', [$blogArticleTag->slug])}}">{{$blogArticleTag->name}}</a>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
                </div>
                <!-- end: Post single item-->
            </div>
            @if(count($item['products']))
                <div class="row">
                    @foreach($item['products'] as $product)
                        <div class="col-lg-3 col-md-3 col-sm-6 col-6">
                            @include('components.product', ['item' => $product])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
    <!-- end: Page Content -->
@endsection
