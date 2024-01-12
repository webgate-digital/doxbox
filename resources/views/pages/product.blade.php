@extends('layout', [
	'ogTitle' => $item['item']['seo_title'] ?: $item['item']['name'],
	'ogDescription' => $item['item']['perex'] ?: $item['item']['seo_description'],
	'robots' => 'index,follow',
    'ogImage' => $item['item']['image_url']
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
      "name": "{{$item['item']['seo_title'] ?: $item['item']['name']}}",
      "image": "{{asset($item['item']['image_url'])}}",
       "description": "{{ $item['item']['seo_description'] ?: $item['item']['perex'] }}",
       "sku": "{{$item['item']['sku'] ?: $item['item']['uuid']}}",
       "brand": {
         "@type": "Brand",
         "name": "{{$item['item']['brand']['name']}}"
       },
       "offers": {
         "@type": "Offer",
         "priceCurrency": "{{\Illuminate\Support\Str::upper($item['item']['currency'])}}",
         "price": "{{$item['item']['retail_price_discounted']}}"
       }
     }




    </script>
@endsection

@section('add-to-cart')
    @if(!$isAvailable)
        <a href="{{route(locale() . '.contact', ['url' => url()->current()])}}" class="button button--primary button--inline">
            {{$translations['products.contact_us_cta']['text']}}
        </a>
    @else
        <add-to-cart
        :item="{{json_encode($item['item'])}}"
            :translations="{{json_encode([
                'Do košíka' => $translations['cart.cta_add']['text'],
                'Na sklade nie je dostatočný počet kusov' => $translations['cart.count_error']['text'],
                'Resetovať' => $translations['cart.reset']['text'],
            ])}}"
        :variants-tree="{{json_encode($variantsTree)}}"
        ></add-to-cart>
    @endif
@endsection

@section('content')
    @include('components.breadcrumbs')

    <section class="section">
        <div class="container">
            <div class="flex flex-wrap -mx-4 flex flex-col-reverse md:flex-row">
                <div class="w-full lg:w-1/2 px-4 order-2 lg:order-1">
                    <div class="fotorama mb-16 lg:mb-0" data-nav="thumbs" data-allowfullscreen="true" data-thumbmargin="8" data-ratio="1" data-width="100%">
                        <a href="{{$item['item']['image_url']}}">
                            <img alt="{{$item['item']['name']}}" src="{{$item['item']['image_url']}}">
                        </a>
                        @foreach($item['item']['gallery'] as $itemImage)
                            <a href="{{$itemImage}}"><img
                                    alt="{{$item['item']['name']}}"
                                    src="{{$itemImage}}"></a>
                        @endforeach
                    </div>
                </div>
                <div class="w-full lg:w-1/2 px-4 order-1 lg:order-2">

                    <h1 class="text-heading-2xs !mb-4">{{$item['item']['name']}}</h1>

                    @if($item['item']['brand'])
                        <h2 class="text-subheading-l text-gray-40 leading-none !mb-6">
                            <a href="{{route(locale() . '.product.list')}}?znacka={{$item['item']['brand']['slug']}}" class="text-gray-40 hover:text-gray-60">
                                {{$item['item']['brand']['name']}}
                            </a>
                        </h2>
                    @endif

                    <div class="product-detail--info">
                        {!!$item['item']['info']!!}
                    </div>

                    <p class="product-detail--price text-subheading-xl">

                        <span @if($item['item']['retail_discount']) class="text-success leading-none" @endif>
                            {{$item['item']['retail_price_discounted_formatted']}}
                        </span>

                        @if($item['item']['badge'])
                            <span class="product-detail--badge" style="--badge-font-color: {{$item['item']['badge']['font_color']}}; --badge-background-color: {{$item['item']['badge']['background_color']}};">
                                {{$item['item']['badge']['name']}}
                            </span>
                        @endif
                    </p>

                    <div class="product-detail--labels">
                        <div class='product-detail--label'>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.0585 6.05835L15.6085 5.60835C15.2258 5.22521 14.7084 5.00689 14.1668 5.00002H5.83351C5.28042 4.99811 4.74945 5.2171 4.35851 5.60835L3.90851 6.05835C3.53757 6.44643 3.33147 6.96318 3.33351 7.50002V17.5C3.33351 17.9603 3.70661 18.3334 4.16684 18.3334H5.00018C5.46042 18.3334 5.83351 17.9603 5.83351 17.5V16.6667H14.1668V17.5C14.1668 17.9603 14.5399 18.3334 15.0002 18.3334H15.8335C16.2937 18.3334 16.6668 17.9603 16.6668 17.5V7.50002C16.66 6.95849 16.4417 6.44111 16.0585 6.05835ZM7.50018 14.1667C7.50018 14.6269 7.12708 15 6.66684 15H5.83351C5.37327 15 5.00018 14.6269 5.00018 14.1667V13.3334C5.00018 12.8731 5.37327 12.5 5.83351 12.5H6.66684C7.12708 12.5 7.50018 12.8731 7.50018 13.3334V14.1667ZM15.0002 14.1667C15.0002 14.6269 14.6271 15 14.1668 15H13.3335C12.8733 15 12.5002 14.6269 12.5002 14.1667V13.3334C12.5002 12.8731 12.8733 12.5 13.3335 12.5H14.1668C14.6271 12.5 15.0002 12.8731 15.0002 13.3334V14.1667ZM15.0002 10H5.00018V7.50002H15.0002V10ZM14.1668 2.50002C14.1668 2.03978 13.7937 1.66669 13.3335 1.66669H6.66684C6.20661 1.66669 5.83351 2.03978 5.83351 2.50002V3.33335H14.1668V2.50002Z" fill="#131416"/>
                            </svg>
                            <span>
                                {{$translations['Poštovné nad 60€ zadarmo']['text']}}
                            </span>
                        </div>
                        <div class='product-detail--label'>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.8844 0.958332L13.8374 3.9C13.9974 4.06484 13.9974 4.32682 13.8374 4.49167L10.8844 7.43333C10.8061 7.51221 10.6995 7.55658 10.5883 7.55658C10.4771 7.55658 10.3704 7.51221 10.2921 7.43333L10.1253 7.26666C10.0438 7.18827 9.99852 7.07965 10.0002 6.96666V5C7.23591 5 4.99503 7.23857 4.99503 10C4.99349 10.8104 5.19114 11.6087 5.57062 12.325C5.65699 12.4875 5.62648 12.6873 5.49554 12.8167L4.88659 13.425C4.79434 13.5162 4.66483 13.5594 4.53623 13.5417C4.40721 13.5221 4.29386 13.4456 4.22758 13.3333C3.03608 11.2717 3.03543 8.73185 4.22587 6.66962C5.4163 4.6074 7.61717 3.33577 10.0002 3.33333V1.425C9.99852 1.31201 10.0438 1.2034 10.1253 1.125L10.2921 0.958332C10.3704 0.879452 10.4771 0.835083 10.5883 0.835083C10.6995 0.835083 10.8061 0.879452 10.8844 0.958332ZM9.11592 12.5667L6.1629 15.5083C6.00291 15.6732 6.00291 15.9352 6.1629 16.1L9.11592 19.0417C9.19424 19.1205 9.30085 19.1649 9.41206 19.1649C9.52327 19.1649 9.62988 19.1205 9.7082 19.0417L9.87503 18.875C9.9565 18.7966 10.0018 18.688 10.0002 18.575V16.6667C12.3832 16.6642 14.584 15.3926 15.7745 13.3304C16.9649 11.2681 16.9642 8.72828 15.7727 6.66667C15.7047 6.55756 15.5916 6.48429 15.4641 6.46667C15.3355 6.44897 15.206 6.4921 15.1137 6.58333L14.4964 7.2C14.368 7.33062 14.3378 7.52873 14.4214 7.69166C14.8046 8.40082 15.0053 9.19408 15.0053 10C15.0053 12.7614 12.7644 15 10.0002 15V13.0333C10.0018 12.9203 9.9565 12.8117 9.87503 12.7333L9.7082 12.5667C9.62988 12.4878 9.52327 12.4434 9.41206 12.4434C9.30085 12.4434 9.19424 12.4878 9.11592 12.5667Z" fill="#131416"/>
                            </svg>
                            <span>
                                {{$translations['Vrátenie zdarma']['text']}}
                            </span>
                        </div>
                    </div>

                    <div class="product-detail--separator"></div>

                    <div class="hidden lg:block">
                        @yield("add-to-cart")
                    </div>
                </div>

            </div>

            <div class="lg:hidden">
                @yield("add-to-cart")
            </div>

            <div class="product-detail--separator"></div>

            <div class="mt-16 product-detail--tabs">
                <h3 class="text-subheading-xl mb-10">{{$translations['products.description_title']['text']}}</h3>
                <div class="wysiwyg-content">
                    {!! $item['item']['description'] !!}
                </div>
            </div>


            @if(isset($item['related']['items']) && count($item['related']['items']) > 0)
            <div class="product-detail--separator"></div>
                <div class="mt-16 product-detail--tabs">
                    <h3 class="text-subheading-xl mb-10">{{$translations['product.related_products']['text']}}</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach(array_slice($item['related']['items'], 0, 4) as $product)
                            @include('components.product', ['item' => $product])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    @include('components.newsletter-section')

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
@endsection
