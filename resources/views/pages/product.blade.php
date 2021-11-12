@extends('layout', [
	'ogTitle' => $item['seo_title'] ?: $item['name'],
	'ogDescription' => $item['seo_description'] ?: $item['perex'],
	'robots' => 'index,follow',
    'ogImage' => $item['image_url']
    ]
)

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
@endsection

@section('json-ld')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "{{$item['seo_title'] ?: $item['name']}}",
      "image": "{{asset($item['image_url'])}}",
       "description": "{{ $item['seo_description'] ?: $item['perex'] }}",
       "sku": "{{$item['sku'] ?: $item['uuid']}}",
       "brand": {
         "@type": "Brand",
         "name": "{{$item['brand']['name']}}"
       },
       "offers": {
         "@type": "Offer",
         "priceCurrency": "{{\Illuminate\Support\Str::upper($item['currency'])}}",
         "price": "{{$item['retail_price_discounted']}}"
       }
     }




    </script>
@endsection

@section('content')

    <!-- Page title -->
    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
            'url' => route(locale().'.product.list'),
            'title' => $translations['menu.products']['text']
        ];
        $breadcrumbs[] = [
            'url' => route(locale() . '.product.category', [$item['category']['slug']]),
            'title' => $item['category']['name']
        ];
        $breadcrumbs[] = [
            'url' => url()->current(),
            'title' => $item['name']
        ];
    @endphp
    @include('components.breadcrumbs')

    <section class="section">
        <div class="container">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full lg:w-1/2 px-4">
                    <div class="fotorama mb-16 lg:mb-0" data-nav="thumbs" data-allowfullscreen="true">
                        <a href="{{$item['image_url']}}"><img alt="{{$item['name']}}" src="{{$item['image_url']}}"></a>
                        @foreach($item['gallery'] as $itemImage)
                            <a href="{{$itemImage}}"><img
                                    alt="{{$item['name']}}"
                                    src="{{$itemImage}}"></a>
                        @endforeach
                    </div>
                </div>
                <div class="w-full lg:w-1/2 px-4">
                    <h1 class="h1">{{$item['name']}}</h1>
                    <p class="product-detail--price">{{$item['retail_price_discounted_formatted']}} @if($item['retail_discount']) <span class="product-detail--price-old">{{$item['retail_price_formatted']}}</span> @endif
                    </p>
                    <p class="">{{$item['perex']}}</p>
                    @if(!$item['count'] && !$item['is_available_for_order'])
                        <a href="{{route(locale() . '.contact', ['url' => url()->current()])}}"
                           class="button button--primary button--inline">{{$translations['products.contact_us_cta']['text']}}</a>
                    @else
                        <add-to-cart uuid="{{$item['uuid']}}" :translations="{{json_encode(['Do košíka' => $translations['cart.cta_add']['text'], 'Na sklade nie je dostatočný počet kusov' => $translations['cart.count_error']['text']])}}"></add-to-cart>
                    @endif
                    <div class="product-detail--separator"></div>
                    @if($item['info'])
                        <div class="wysiwyg-content">
                            {!! $item['info'] !!}
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-16">
                <h3 class="h3">{{$translations['products.description_title']['text']}}</h3>
                <div class="wysiwyg-content">
                    {!! $item['description'] !!}
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
@endsection
