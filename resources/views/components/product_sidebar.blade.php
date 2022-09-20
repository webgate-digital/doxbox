<aside id="filter-bar" class="sticky top-8">
    <div class="border-b border-white">
        <h3 class="text-subheading-xl md:hidden">
            {{$translations['filter.title']['text']}}
        </h3>
    </div>
    <form method="get" action="#list" id="filter-form" class="flex flex-col">
        @foreach($availableAttributes as $attribute)
            <div class="mb-8 flex flex-col">
                <p class="h5 ">{{$attribute['name']}}</p>
                @foreach ($attribute['values'] as $attributeValue)
                    <div onclick="document.getElementById('filter-form').submit();" class="mb-4 flex items-center gap-4 cursor-pointer filter-bar--attribute">
                        <input class="cursor-pointer" id="attribute_{{$attributeValue['uuid']}}"
                            name="attributes[{{$attribute['uuid']}}][]" type="checkbox"
                            value="{{$attributeValue['uuid']}}"
                            @if(isset(request()->get('attributes')[$attribute['uuid']]) && (is_array((request()->get('attributes')[$attribute['uuid']]))) && (in_array((string)$attributeValue['uuid'], request()->get('attributes')[$attribute['uuid']]))) checked @endif>
                        <label class="cursor-pointer" for="attribute_{{$attributeValue['uuid']}}">&nbsp;{{$attributeValue['name']}}</label>
                    </div>

                    @if($loop->index === 4)
                        <a href="#" class="filter-bar--show-more" onclick="event.preventDefault();event.target.classList.toggle('active');" data-show-more-text="{{$translations['Zobraziť viac']['text']}}" data-show-less-text="{{$translations['Zobraziť menej']['text']}}"></a>
                    @endif
                @endforeach
            </div>
        @endforeach
        
        <p class="h5">{{$translations['filter.price_title']['text']}} (<span id="filter_sidebar_min_price">{{request()->get('min_price', $filterPrices['min_price'])}},00 {{$setup['currencies'][session()->get('currency')]['symbol']}}</span>
            -
            <span
                id="filter_sidebar_max_price">{{request()->get('max_price', $filterPrices['max_price'])}},00 {{$setup['currencies'][session()->get('currency')]['symbol']}}</span>)
        </p>
        <input type="hidden" name="sort"
               value="{{request()->get('sort', $setup['api']['defaults']['sort']['products'])}}">
        <price-range class="mx-4 mb-8"
            :price="{{json_encode([(int)request()->get('min_price', $filterPrices['min_price']), (int)request()->get('max_price', $filterPrices['max_price'])])}}"
            :max-price="{{$filterPrices['max_price']}}"
            :currency="{{json_encode($setup['currencies'][session()->get('currency')])}}"></price-range>

        <button type="submit" class="button button--primary rounded-xl">{{$translations['filter.cta']['text']}}</button>
        <a href="{{route(locale() . '.product.list')}}#list" class="mt-4 text-primary font-light text-center">
            {{$translations['filter.cta_cancel']['text']}}
        </a>
    </form>
</aside>

<div class="overlay" onclick="document.getElementById('filter-bar').classList.remove('is-open');"></div>
