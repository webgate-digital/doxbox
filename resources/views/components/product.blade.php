<div class="product-box">
    <div class="product-box--image">
        <a href="{{route(locale() . '.product.detail', [$item['category']['slug'], $item['slug']])}}">
        <img src="{{$item['image_url']}}" alt="{{$item['name']}}">
        </a>
    </div>
    <h4 class="product-box--name">
        <a href="{{route(locale() . '.product.detail', [$item['category']['slug'], $item['slug']])}}">{{$item['name']}}</a>
    </h4>
    <p class="product-box--price">{{$item['retail_price_discounted_formatted']}} @if($item['retail_discount']) <span class="product-box--price-old">{{$item['retail_price_formatted']}}</span> @endif
    </p>
    <a href="{{route(locale() . '.product.detail', [$item['category']['slug'], $item['slug']])}}" class="product-box--cta button button--primary">{{$translations['product.show_cta']['text']}}</a>
</div>
