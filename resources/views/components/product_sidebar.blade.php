<aside id="filter-bar" class="sticky top-8">
    <form method="get" action="#list" id="filter-form">
        <div class="border-b border-white">
            <h3 class="text-subheading-xl md:hidden">
                {{ $translations['filter.title']['text'] }}
            </h3>
        </div>
        <div class="filter-form-body">
            @foreach ($availableAttributes as $attribute)
                <div class="mb-10 flex flex-col attribute-block{{ $loop->index < 2 ? ' opened' : '' }}">
                    <div class="text-subheading-l mb-8 flex justify-between align-center attribute-block__title">
                        <div>{{ $attribute['name'] }}</div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.0801 6.83003C20.1739 6.73537 20.3017 6.68213 20.4351 6.68213C20.5684 6.68213 20.6962 6.73537 20.7901 6.83003L21.8501 7.89003C21.9467 7.98076 22.0016 8.10744 22.0016 8.24003C22.0016 8.37261 21.9467 8.49929 21.8501 8.59003L12.6601 17.78C12.5195 17.9207 12.3289 17.9999 12.1301 18H11.8701C11.6712 17.9999 11.4806 17.9207 11.3401 17.78L2.15005 8.59003C2.05338 8.49929 1.99854 8.37261 1.99854 8.24003C1.99854 8.10744 2.05338 7.98076 2.15005 7.89003L3.21005 6.83003C3.30394 6.73537 3.43174 6.68213 3.56505 6.68213C3.69837 6.68213 3.82617 6.73537 3.92005 6.83003L12.0001 14.91L20.0801 6.83003Z" fill="#131416"/>
                        </svg>                            
                    </div>
                    <div class="attribute-block__values flex flex-col">
                        @foreach ($attribute['values'] as $attributeValue)
                            <div onclick="document.getElementById('filter-form').submit();"
                                class="mb-4 flex items-center gap-4 cursor-pointer filter-bar--attribute">
                                <input class="cursor-pointer" id="attribute_{{ $attributeValue['uuid'] }}"
                                    name="attributes[{{ $attribute['uuid'] }}][]" type="checkbox"
                                    value="{{ $attributeValue['uuid'] }}" @if (isset(request()->get('attributes')[$attribute['uuid']]) &&
                                        is_array(request()->get('attributes')[$attribute['uuid']]) &&
                                        in_array((string) $attributeValue['uuid'], request()->get('attributes')[$attribute['uuid']])) checked @endif>
                                <label class="cursor-pointer text-body-m"
                                    for="attribute_{{ $attributeValue['uuid'] }}">&nbsp;{{ $attributeValue['name'] }}</label>
                            </div>

                            @if ($loop->index === 4)
                                <a href="#" class="filter-bar--show-more"
                                    onclick="event.preventDefault();event.target.classList.toggle('active');"
                                    data-show-more-text="{{ $translations['Zobraziť viac']['text'] }}"
                                    data-show-less-text="{{ $translations['Zobraziť menej']['text'] }}"></a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="filter-form-footer">
            @php
                $isFilterActive = request()->has('attributes') || request()->has('min_price') || request()->has('max_price');
            @endphp
            <p class="h5">{{ $translations['filter.price_title']['text'] }} (<span
                    id="filter_sidebar_min_price">{{ request()->get('min_price', $filterPrices['min_price']) }},00
                    {{ $setup['currencies'][session()->get('currency')]['symbol'] }}</span>
                -
                <span id="filter_sidebar_max_price">{{ request()->get('max_price', $filterPrices['max_price']) }},00
                    {{ $setup['currencies'][session()->get('currency')]['symbol'] }}</span>)
            </p>
            <input type="hidden" name="sort"
                value="{{ request()->get('sort', $setup['api']['defaults']['sort']['products']) }}">
            <price-range class="mx-4 mb-8 price-change"
                :price="{{ json_encode([(int) request()->get('min_price', $filterPrices['min_price']), (int) request()->get('max_price', $filterPrices['max_price'])]) }}"
                :max-price="{{ $filterPrices['max_price'] }}"
                :currency="{{ json_encode($setup['currencies'][session()->get('currency')]) }}"></price-range>

            <button type="submit" style="display: none"
                class="button button--secondary rounded-xl">{{ $translations['filter.cta']['text'] }}</button>
            <a {{ $isFilterActive ? 'href=' . request()->path() : '' }} class="button border border-primary text-primary rounded-lg{{!$isFilterActive ? ' disabled' : ''}}">
                {{ $translations['filter.cta_cancel']['text'] }}
            </a>
        </div>
    </form>
</aside>

<div class="overlay" onclick="document.getElementById('filter-bar').classList.remove('is-open');"></div>
