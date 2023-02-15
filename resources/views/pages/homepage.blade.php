@extends('layout', ['robots' => 'index,follow'])

@section('content')
    <main>
        @if($translations['notification.show']['text'] === 'true')
            <a class="py-5 px-4 text-center w-full block text-white text-subheading-s" href="{{$translations['notification.link']['text']}}" style="background-color: {{ $translations['notification.color']['text'] }}">
                {{$translations['notification.text']['text']}}
            </a>
        @endif

        <section class="big-banner" style="--bg-image: url('https://storage-doxbox.fra1.cdn.digitaloceanspaces.com/images/Banners/banner-0.webp')">
            <div class="container">
                <h1 class="text-heading-xl mb-10">
                    {!! $translations['slider.sale']['text'] !!}
                </h1>
                <a href="{{route(locale() . '.product.list')}}" class="button button--primary rounded-lg button--inline">
                    {!! $translations['slider.go_to_shop']['text'] !!}
                </a>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="text-heading-2xs md:mb-20 mb-6">
                    {{$translations['products.top_selling_title']['text']}}
                </h2>
                <div class="product-container product-container--homepage">
                    @foreach($products as $product)
                        @include('components.product', ['item' => $product])
                    @endforeach
                </div>
                <div class="text-center mt-10 md:mt-20">
                    <a href="{{route(locale() . '.product.list')}}" class="button border border-primary text-primary rounded-lg button--inline">
                        {{$translations['products.show_all_cta']['text']}}
                    </a>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="text-heading-2xs md:mb-20 mb-6">
                    {{$translations['categories.title']['text']}}
                </h2>
                <div class="category-container">
                    @foreach(array_slice($categories, 0, 11) as $category)
                        @include('components.category', ['item' => $category])
                    @endforeach

                    <a href="{{route(locale() . '.categories')}}" class="category-box">
                        <div class="category-box--image">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.32513 33.3147V30.6481C5.32513 29.9117 5.92227 29.3147 6.65888 29.3147H48.005V23.9814C48.0125 23.448 48.3373 22.9703 48.8307 22.7672C49.3242 22.5641 49.8913 22.6746 50.2723 23.0481L58.2748 31.0481C58.7864 31.5756 58.7864 32.4139 58.2748 32.9414L50.2723 40.9414C49.8878 41.3182 49.3143 41.4269 48.8185 41.217C48.3227 41.0071 48.0018 40.5197 48.005 39.9814V34.6481H6.65888C5.92227 34.6481 5.32513 34.0511 5.32513 33.3147Z" fill="#131416"/>
                            </svg>
                        </div>
                        <h4 class="category-box--name">
                            {{$translations['Všetky kategórie']['text']}}
                        </h4>
                    </a>                    
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2 class="text-heading-2xs md:mb-20 mb-6">
                    {{$translations['brands.title']['text']}}
                </h2>
                <div class="category-container">
                    @foreach(array_slice($brands, 0, 7) as $category)
                        @include('components.brand', ['item' => $category])
                    @endforeach

                    <a href="{{route(locale() . '.brands')}}" class="category-box">
                        <div class="category-box--image">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.32513 33.3147V30.6481C5.32513 29.9117 5.92227 29.3147 6.65888 29.3147H48.005V23.9814C48.0125 23.448 48.3373 22.9703 48.8307 22.7672C49.3242 22.5641 49.8913 22.6746 50.2723 23.0481L58.2748 31.0481C58.7864 31.5756 58.7864 32.4139 58.2748 32.9414L50.2723 40.9414C49.8878 41.3182 49.3143 41.4269 48.8185 41.217C48.3227 41.0071 48.0018 40.5197 48.005 39.9814V34.6481H6.65888C5.92227 34.6481 5.32513 34.0511 5.32513 33.3147Z" fill="#131416"/>
                            </svg>
                        </div>
                        <h4 class="category-box--name">
                            {{$translations['Všetky značky']['text']}}
                        </h4>
                    </a>                    
                </div>
            </div>
        </section>

        <div class="promo-section promo-section--big bg-gray-5" style="--bg-image: url(https://storage-doxbox.fra1.cdn.digitaloceanspaces.com/images/Banners/banner-1
            <div>
                <div style="max-width:600px" class="mx-auto">
                    <h2 class="text-heading-xs leading-none">
                        {!!$translations['homepage.product.1.title']['text']!!}
                    </h2>
                    <p class="text-body-xl">
                        {!!$translations['homepage.product.1.description']['text']!!}
                    </p>
                    <a href="{{$translations['homepage.product.1.link']['text']}}" class="button button--primary button--inline rounded-lg">
                        {!!$translations['homepage.product.1.cta']['text']!!}
                    </a>
                </div>
            </div>
        </div>
        
        <div class="promo-section">
            <div class="bg-gray-5" style="--bg-image: url(https://storage-doxbox.fra1.cdn.digitaloceanspaces.com/images/Banners/banner-2.webp)">
                <div style="max-width:600px" class="mx-auto">
                    <h2 class="text-heading-xs leading-none">
                        {!!$translations['homepage.product.2.title']['text']!!}
                    </h2>
                    <p class="text-body-xl">
                        {!!$translations['homepage.product.2.description']['text']!!}
                    </p>
                    <a href="{{$translations['homepage.product.2.link']['text']}}" class="button button--primary button--inline rounded-lg">
                        {!!$translations['homepage.product.2.cta']['text']!!}
                    </a>
                </div>
            </div>
            <div class="bg-secondary text-white" style="--bg-image: url(https://storage-doxbox.fra1.cdn.digitaloceanspaces.com/images/Banners/banner-3.webp)">
                <div style="max-width:600px" class="mx-auto">
                    <h2 class="text-heading-xs leading-none">
                        {!!$translations['homepage.product.3.title']['text']!!}
                    </h2>
                    <p class="text-body-xl">
                        {!!$translations['homepage.product.3.description']['text']!!}
                    </p>
                    <a href="{{$translations['homepage.product.3.link']['text']}}" class="button button--primary button--inline rounded-lg">
                        {!!$translations['homepage.product.3.cta']['text']!!}
                    </a>
                </div>
            </div>
            
            <div class="bg-secondary text-white" style="--bg-image: url(https://storage-doxbox.fra1.cdn.digitaloceanspaces.com/images/Banners/banner-4.webp)">
                <div style="max-width:600px" class="mx-auto">
                    <h2 class="text-heading-xs leading-none">
                        {!!$translations['homepage.product.4.title']['text']!!}
                    </h2>
                    <p class="text-body-xl">
                        {!!$translations['homepage.product.4.description']['text']!!}
                    </p>
                    <a href="{{$translations['homepage.product.4.link']['text']}}" class="button button--primary button--inline rounded-lg">
                        {!!$translations['homepage.product.4.cta']['text']!!}
                    </a>
                </div>
            </div>

            <div class="bg-gray-5" style="--bg-image: url(https://storage-doxbox.fra1.cdn.digitaloceanspaces.com/images/Banners/banner-5.webp)">
                <div style="max-width:600px" class="mx-auto">
                    <h2 class="text-heading-xs leading-none">
                        {!!$translations['homepage.product.5.title']['text']!!}
                    </h2>
                    <p class="text-body-xl">
                        {!!$translations['homepage.product.5.description']['text']!!}
                    </p>
                    <a href="{{$translations['homepage.product.5.link']['text']}}" class="button button--primary button--inline rounded-lg">
                        {!!$translations['homepage.product.5.cta']['text']!!}
                    </a>
                </div>
            </div>
        </div>

        <div class="promo-section promo-section--big bg-secondary text-white mb-32" style="--bg-image: url(https://storage-doxbox.fra1.cdn.digitaloceanspaces.com/images/Banners/banner-6.webp)">
            <div>
                <div style="max-width:600px" class="mx-auto">
                    <h2 class="text-heading-xs leading-none">
                        {!!$translations['homepage.product.6.title']['text']!!}
                    </h2>
                    <p class="text-body-xl">
                        {!!$translations['homepage.product.6.description']['text']!!}
                    </p>
                    <a href="{{$translations['homepage.product.6.link']['text']}}" class="button button--primary button--inline rounded-lg">
                        {!!$translations['homepage.product.6.cta']['text']!!}
                    </a>
                </div>
            </div>
        </div>

        @include('components.newsletter-section')
    </main>
@endsection
