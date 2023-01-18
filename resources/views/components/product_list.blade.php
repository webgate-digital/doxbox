<form action="" class="flex flex-col mb-4 items-end gap-4 product-list">
    <select name="sort" onchange="this.form.submit()">
        <option @if(request()->get('sort', $setup['api']['defaults']['sort']['products']) === 'score_desc') selected
                @endif value="score_desc">{{$translations['sort.score_desc']['text']}}</option>
        <option
            @if(request()->get('sort', $setup['api']['defaults']['sort']['products']) === 'created_at_desc') selected
            @endif value="created_at_desc">{{$translations['sort.created_at_desc']['text']}}</option>
        <option
            @if(request()->get('sort', $setup['api']['defaults']['sort']['products']) === 'retail_price_discounted_desc') selected
            @endif value="retail_price_discounted_desc">{{$translations['sort.retail_price_discounted_desc']['text']}}</option>
        <option
            @if(request()->get('sort', $setup['api']['defaults']['sort']['products']) === 'retail_price_discounted_asc') selected
            @endif value="retail_price_discounted_asc">{{$translations['sort.retail_price_discounted_asc']['text']}}</option>
    </select>
    <input type="hidden" name="min_price" value="{{request()->get('min_price', $filterPrices['min_price'])}}">
    <input type="hidden" name="max_price" value="{{request()->get('max_price', $filterPrices['max_price'])}}">
    <div class="product-container">
        @foreach($products as $product)
            @include('components.product', ['item' => $product])
        @endforeach
    </div>
</form>