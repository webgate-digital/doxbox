@php
    // Calculate discount percentage for V3
    $isV3 = isset($item['_highlightResult']);
    if($isV3) {
        $salePrice = floatval(str_replace(',', '.', str_replace(' €', '', $item['sale_price_eur_with_vat_formatted'])));
        $retailPrice = floatval(str_replace(',', '.', str_replace(' €', '', $item['retail_price_eur_with_vat_formatted'])));
        if ($salePrice != $retailPrice && $retailPrice > 0) {
            $discountPercentage = round(100 - ($salePrice / $retailPrice * 100));
        }
    } else {
        if ($item['retail_price'] != $item['retail_price_discounted'] && $item['retail_price'] > 0) {
            $discountPercentage = round(100 - ($item['retail_price_discounted'] / $item['retail_price'] * 100));
        }
    }
@endphp
<a href="{{\App\Http\Controllers\ProductController::buildProductRoute($item)}}" class="product-box{{isset($item['is_sold_out']) && $item['is_sold_out'] ? ' product-box--sold-out' : ''}}">
    <div class="product-box--image">
        <img src="{{$item['image_url']}}" alt="{{$item['name']}}">
        <div class="product-box--cta button button--primary">
            {{$translations['product.show_cta']['text']}}
        </div>
        @if(isset($item['badge']) && $item['badge'])
            <div class="product-box--badge" style="--badge-font-color: {{$item['badge']['font_color']}}; --badge-background-color: {{$item['badge']['background_color']}};">
                {{$item['badge']['name']}}
            </div>
        @endif
        @if(isset($discountPercentage))
            <div class="product-box--badge product-box--badge-discount">
                - {{$discountPercentage}}%
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
            $isV3 = isset($item['_highlightResult']);
            $hasVariants = (isset($item['variants']) && count($item['variants'])) > 0;

            if ($hasVariants) {
                if ($isV3) {
                    $minPrice = null;
                    $maxPrice = null;
                    foreach ($item['variants'] as $variant) {
                        $price = floatval(str_replace(',', '.', str_replace(' €', '', $variant['sale_price_eur_with_vat_formatted'])));
                        if ($minPrice === null || $price < $minPrice) {
                            $minPrice = $price;
                        }
                        if ($maxPrice === null || $price > $maxPrice) {
                            $maxPrice = $price;
                        }
                    }
                    $minPrice = number_format($minPrice, 2, ',', '') . ' €';
                    $maxPrice = number_format($maxPrice, 2, ',', '') . ' €';
                } else {
                    $minPrice = $item['variants_min_price_with_vat_formatted'];
                    $maxPrice = $item['variants_max_price_with_vat_formatted'];
                }

                if ($minPrice === $maxPrice) {
                    $priceString = $minPrice;
                } else {
                    $priceString = $minPrice . ' - ' . $maxPrice;
                }
            } else {
                if ($isV3) {
                    $priceString = $item['sale_price_eur_with_vat_formatted'];
                    $salePrice = floatval(str_replace(',', '.', str_replace(' €', '', $item['sale_price_eur_with_vat_formatted'])));
                    $retailPrice = floatval(str_replace(',', '.', str_replace(' €', '', $item['retail_price_eur_with_vat_formatted'])));
                    if ($salePrice != $retailPrice) {
                        $oldPrice = $retailPrice;
                        $oldPriceFormatted = number_format($oldPrice, 2, ',', '') . ' €';
                    }
                } else {
                    $priceString = $item['retail_price_discounted_formatted'];
                    if ($item['retail_price'] != $item['retail_price_discounted']) {
                        $oldPrice = $item['retail_price'];
                        $oldPriceFormatted = $item['retail_price_formatted'];
                    }
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
