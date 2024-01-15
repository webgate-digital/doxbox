
<a href="{{\App\Http\Controllers\ProductController::buildProductRoute($item)}}" class="product-box{{isset($item['is_sold_out']) && $item['is_sold_out'] ? ' product-box--sold-out' : ''}}">
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
        @if(isset($item['is_sold_out']) && $item['is_sold_out'])
            <div class="product-box--badge">
                Vypredané
            </div>
        @endif
    </div>
    <h4 class="product-box--name">
        {{$item['name']}}
    </h4>
    <p class="product-box--price">
        @php
            $hasVariants = (isset($item['variants']) && count($item['variants'])) > 0;
            if ($hasVariants) {
                $minPrice = $item['variants_min_price_with_vat_formatted'];
                $maxPrice = $item['variants_max_price_with_vat_formatted'];

                if ($minPrice === $maxPrice) {
                    $priceString = $minPrice;
                } else {
                    $priceString = $minPrice . ' - ' . $maxPrice;
                }
            } else {
                $priceString = $item['retail_price_discounted_formatted'];
                if ($item['retail_price'] != $item['retail_price_discounted']) {
                    $oldPrice = $item['retail_price'];
                    $oldPriceFormatted = $item['retail_price_formatted'];
                }
            }
        @endphp
        {{$priceString}}
        @if(isset($oldPrice))
            <span class="product-box--price-old">
                {{$oldPriceFormatted}}
            </span>
        @endif
    </p>
</a>
