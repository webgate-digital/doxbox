
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
            $allProducts = array_merge($item['variants'], [$item]);
            $productWithMinPrice = collect($allProducts)->sortBy('retail_price_discounted')->first();
            $productWithMaxPrice = collect($allProducts)->sortBy('retail_price_discounted')->last();
            $priceString = '';
            if($productWithMinPrice['retail_price_discounted'] == $productWithMaxPrice['retail_price_discounted']) {
                $priceString = $productWithMinPrice['retail_price_discounted_formatted'];
            } else {
                $priceString = $productWithMinPrice['retail_price_discounted_formatted'] . ' - ' . $productWithMaxPrice['retail_price_discounted_formatted'];
            }
        @endphp
        {{$priceString}}
    </p>
</a>
