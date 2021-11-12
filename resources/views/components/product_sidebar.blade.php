<aside class="sticky top-8">
    <p class="h4">{{$translations['filter.title']['text']}}</p>
    <p class="h5">{{$translations['filter.price_title']['text']}} (<span id="filter_sidebar_min_price">{{request()->get('min_price', $filterPrices['min_price'])}},00 {{$setup['currencies'][session()->get('currency')]['symbol']}}</span>
        -
        <span
            id="filter_sidebar_max_price">{{request()->get('max_price', $filterPrices['max_price'])}},00 {{$setup['currencies'][session()->get('currency')]['symbol']}}</span>)
    </p>
    <form method="get" action="#list" id="filter-form">
        <input type="hidden" name="sort"
               value="{{request()->get('sort', $setup['api']['defaults']['sort']['products'])}}">
        <price-range class="mb-8"
                     :price="{{json_encode([(int)request()->get('min_price', $filterPrices['min_price']), (int)request()->get('max_price', $filterPrices['max_price'])])}}"
                     :max-price="{{$filterPrices['max_price']}}"
                     :currency="{{json_encode($setup['currencies'][session()->get('currency')])}}"></price-range>
        @foreach($attributes as $attribute)
            <p class="h5">{{$attribute['name']}}</p>
            @foreach ($attribute['values'] as $attributeValue)
                <div onclick="document.getElementById('filter-form').submit();" class="mb-8">
                    <input id="attribute_{{$attributeValue['uuid']}}"
                           name="attributes[{{$attribute['uuid']}}][]" type="checkbox"
                           value="{{$attributeValue['uuid']}}"
                           @if(isset(request()->get('attributes')[$attribute['uuid']]) && (is_array((request()->get('attributes')[$attribute['uuid']]))) && (in_array((string)$attributeValue['uuid'], request()->get('attributes')[$attribute['uuid']]))) checked @endif>
                    <label for="attribute_{{$attributeValue['uuid']}}">&nbsp;{{$attributeValue['name']}}</label>
                </div>
            @endforeach
        @endforeach
        <button type="submit" class="button button--secondary">{{$translations['filter.cta']['text']}}</button>
        <a href="{{route(locale() . '.product.list')}}#list" class="mt-4 flex justify-end"><small>{{$translations['filter.cta_cancel']['text']}}</small></a>
    </form>
</aside>
