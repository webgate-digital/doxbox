<a href="{{route(locale() . '.product.detail', [$item['category']['slug'], $item['slug']])}}" class="product-box">
    <div class="product-box--image">
        <img src="{{$item['image_url']}}" alt="{{$item['name']}}">
        <div class="product-box--cta button button--primary">
            {{$translations['product.show_cta']['text']}}
        </div>
        @if($item['badge'])
            <div class="product-box--badge" style="--badge-font-color: {{$item['badge']['font_color']}}; --badge-background-color: {{$item['badge']['background_color']}};">
                {{$item['badge']['name']}}
            </div>
        @endif
    </div>
    <h4 class="product-box--name">
        {{$item['name']}}
    </h4>
    <p class="product-box--price">
        <span @if($item['retail_discount']) class="text-success" @endif>
            {{$item['retail_price_discounted_formatted']}}
        </span>
        @if($item['retail_discount'])
            <span class="product-box--price-old">
                {{$item['retail_price_formatted']}}
            </span>
        @endif
    </p>
</a>
