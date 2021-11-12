<form action="" class="flex mb-4 justify-end">
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
</form>

<div class="product-container mb-32 lg:mb-0">
    @foreach($products as $product)
        <div class="w-1/2 lg:w-1/3">
            @include('components.product', ['item' => $product])
        </div>
    @endforeach
</div>
