@extends('layout', ['ogTitle' => $translations['cart.shipping_and_payment_title']['text']])

@section('content')

    @include('components.order_loading', ['text' => $translations['general.loading']['text']])

    @include('components.freeshipping')

    @if($errors->any())
        <div class="alert alert--danger">
            {{$translations['general.form_validation_error']['text']}}
        </div>
    @endif

    @include('components.cart_steps')

    <form action="{{route(locale() . '.cart.select_shipping_country')}}"
          method="post"
          id="selectDefaultShippingCountryForm">
        @csrf
        <input type="hidden" name="default_shipping_country"
               id="default_shipping_country_input">
    </form>

    <section class="section">
        <div class="container">
            <h1 class="h1">
                {{$translations['cart.shipping_and_payment_title']['text']}}
            </h1>
            <shipping-and-payment
                :translations="{{json_encode([
                'cart.shipping_country_title' => $translations['cart.shipping_country_title']['text'],
                'cart.choose_shipping_title' => $translations['cart.choose_shipping_title']['text'],
                'cart.estimated_delivery_time_between' => $translations['cart.estimated_delivery_time_between']['text'],
                'cart.free_price' => $translations['cart.free_price']['text'],
                'cart.choose_payment_title_first' => $translations['cart.choose_payment_title_first']['text'],
                'cart.choose_payment_title' => $translations['cart.choose_payment_title']['text'],
                'cart.cta_choose' => $translations['cart.cta_choose']['text'],
                'cart.gdpr_accept_start' => $translations['cart.gdpr_accept_start']['text'],
                'cart.gdpr_accept_toc' => $translations['cart.gdpr_accept_toc']['text'],
                'cart.gdpr_accept_middle' => $translations['cart.gdpr_accept_middle']['text'],
                'cart.gdpr_accept_self' => $translations['cart.gdpr_accept_self']['text'],
                'cart.cta_continue_to_checkout' => $translations['cart.cta_continue_to_checkout']['text']
            ])}}"
                init-url="{{route(locale() . '.cart.shipping.init')}}"
                shipping-country-url="{{route(locale() . '.cart.select_shipping_country')}}"
                shipping-url="{{route(locale().'.cart.select_shipping')}}"
                payment-url="{{route(locale().'.cart.select_payment')}}"
                continue-to-checkout-url="{{route(locale().'.cart.continue_to_checkout')}}"
                locale="{{locale()}}"
                toc-link="{{$documentSettings['toc_document']['value']}}"
                gdpr-link="{{route(locale() . '.page', [$gdprPage['slug']])}}"
            >

            </shipping-and-payment>
{{--            <div class="flex flex-wrap -mx-4">--}}
{{--                <div class="w-full lg:w-1/2 px-4">--}}
{{--                    <h2 class="h2">--}}
{{--                        {{$translations['cart.shipping_country_title']['text']}}--}}
{{--                    </h2>--}}
{{--                    @foreach($shippingCountries as $item)--}}
{{--                        <div class="list-item">--}}
{{--                            <input type="radio" name="default_shipping_country"--}}
{{--                                   value="{{$item['uuid']}}"--}}
{{--                                   class="list-item--radio"--}}
{{--                                   onchange="selectDefaultShippingCountry('{{$item['uuid']}}')"--}}
{{--                                   @if($item['uuid'] === $shippingCountry['uuid']) checked--}}
{{--                                   @endif--}}
{{--                                   id="{{$item['uuid']}}_default_shipping_country"/>--}}
{{--                            <label class="cursor-pointer"--}}
{{--                                   for="{{$item['uuid']}}_default_shipping_country"><b>{{$item['name']}}--}}
{{--                                </b></label>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                    <form action="{{route(locale().'.cart.select_shipping')}}"--}}
{{--                          id="vyber-dopravy"--}}
{{--                          method="post">--}}
{{--                        @csrf--}}
{{--                        <h2 class="h2 mt-8">--}}
{{--                            {{$translations['cart.choose_shipping_title']['text']}}--}}
{{--                        </h2>--}}

{{--                        @foreach($shippingTypes as $shipping)--}}
{{--                            <div class="list-item">--}}
{{--                                <input type="radio" name="shipping_type"--}}
{{--                                       value="{{$shipping['uuid']}}"--}}
{{--                                       class="list-item--radio"--}}
{{--                                       @if(!\Illuminate\Support\Str::contains($shipping['uuid'], 'PACKETA') && !\Illuminate\Support\Str::contains($shipping['uuid'], 'ULOZENKA')) onchange="document.getElementById('order-loading-wrapper').style.display = 'flex'; this.form.submit();"--}}
{{--                                       @else--}}
{{--                                       @if(\Illuminate\Support\Str::contains($shipping['uuid'], 'PACKETA'))--}}
{{--                                       onclick="Packeta.Widget.pick('{{config('packeta.api_key')}}', showSelectedPickupPoint, {country: '{{\Illuminate\Support\Str::lower(session()->get('shipping_country'))}}', language: '{{locale()}}'})"--}}
{{--                                       @endif--}}
{{--                                       @if(\Illuminate\Support\Str::contains($shipping['uuid'], 'ULOZENKA'))--}}
{{--                                       onclick="showUlozenka()"--}}
{{--                                       @endif--}}
{{--                                       @endif--}}
{{--                                       @if($shipping['uuid'] === session('shipping_type', old('shipping_type', null))) checked--}}
{{--                                       @endif--}}
{{--                                       id="{{$shipping['uuid']}}_shipping"/>--}}
{{--                                <label--}}
{{--                                    class="list-item--label @if(\Illuminate\Support\Str::contains($shipping['uuid'], 'PACKETA')) packeta-selector-open @endif"--}}
{{--                                    for="{{$shipping['uuid']}}_shipping">--}}
{{--                                                <span class="list-item--label---column">--}}
{{--                                                    <b>{{$shipping['name']}}</b> @if($shipping['info'])--}}
{{--                                                        <br><small> {{$shipping['info']}} </small> @endif--}}
{{--                                                    @if(\Illuminate\Support\Str::contains(session()->get('shipping_type'), 'PACKETA'))--}}
{{--                                                        <small--}}
{{--                                                            class="packeta-selector-branch-name"> - {{session()->get('packeta-selector-branch-name')}}</small>--}}
{{--                                                    @endif--}}
{{--                                                    @if($shipping['estimated_delivery_time_from'] && $shipping['estimated_delivery_time_to'])--}}
{{--                                                        <br>--}}
{{--                                                        <small>{{$translations['cart.estimated_delivery_time_between']['text']}} {{$shipping['estimated_delivery_time_from']}} - {{$shipping['estimated_delivery_time_to']}}</small>--}}
{{--                                                    @endif--}}
{{--                                                </span>--}}
{{--                                    <b class="list-item--label---column">--}}
{{--                                        {{$shipping['price'] && ($cart['free_shipping'] >= 0 || !$cart['free_shipping_allowed']) ? $shipping['price_formatted'] : $translations['cart.free_price']['text']}}--}}
{{--                                    </b>--}}
{{--                                </label>--}}
{{--                                --}}{{--                                @if(\Illuminate\Support\Str::contains($shipping['uuid'], 'ULOZENKA'))--}}
{{--                                --}}{{--                                    <div class="text-center" id="ulozenka-branch-loader"--}}
{{--                                --}}{{--                                         style="display: none;"><i--}}
{{--                                --}}{{--                                            class="fas fa-spinner rotating"></i></div>--}}
{{--                                --}}{{--                                    <div id="ulozenka-branch-select-options"--}}
{{--                                --}}{{--                                         style="margin-bottom: .5rem"></div>--}}
{{--                                --}}{{--                                @endif--}}
{{--                            </div>--}}
{{--                        @endforeach--}}

{{--                        <input type="hidden" name="packeta-selector-branch-name"--}}
{{--                               class="packeta-selector-branch-name"--}}
{{--                               id="packeta-selector-branch-name"--}}
{{--                               @if(\Illuminate\Support\Str::contains(session()->get('shipping_type'), 'PACKETA') && session()->get('packeta-selector-branch-name')) value="{{session()->get('packeta-selector-branch-name')}}" @endif>--}}
{{--                        <input type="hidden" name="packeta-selector-branch-id"--}}
{{--                               class="packeta-selector-branch-id"--}}
{{--                               id="packeta-selector-branch-id"--}}
{{--                               @if(\Illuminate\Support\Str::contains(session()->get('shipping_type'), 'PACKETA') && session()->get('packeta-selector-branch-id')) value="{{session()->get('packeta-selector-branch-id')}}" @endif>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--                <div class="w-full lg:w-1/2 px-4">--}}
{{--                    <form action="{{route(locale() . '.cart.select_payment')}}"--}}
{{--                          method="post">--}}
{{--                        @csrf--}}
{{--                        @if(!session('shipping_type'))--}}
{{--                            <h2 class="h2 mt-8 lg:mt-0">--}}
{{--                                {{$translations['cart.choose_payment_title_first']['text']}}--}}
{{--                            </h2>--}}
{{--                        @else--}}
{{--                            <h2 class="h2 mt-8 lg:mt-0">--}}
{{--                                {{$translations['cart.choose_payment_title']['text']}}--}}
{{--                            </h2>--}}
{{--                        @endif--}}
{{--                        @foreach($paymentTypes as $payment)--}}
{{--                            <div class="list-item">--}}
{{--                                <input type="radio" name="payment_type"--}}
{{--                                       value="{{$payment['uuid']}}"--}}
{{--                                       class="list-item--radio"--}}
{{--                                       @if(session('shipping_type'))--}}
{{--                                       onchange="document.getElementById('order-loading-wrapper').style.display = 'flex'; this.form.submit();"--}}
{{--                                       @endif--}}
{{--                                       @if(!session('shipping_type'))--}}
{{--                                       disabled--}}
{{--                                       @endif--}}
{{--                                       @if($payment['uuid'] === session('payment_type', old('payment_type', null))) checked--}}
{{--                                       @endif--}}
{{--                                       id="{{$payment['uuid']}}_payment"/>--}}
{{--                                <label--}}
{{--                                    class="@if(!session('shipping_type')) opacity-50 @endif flex justify-between w-full -mx-2 items-center"--}}
{{--                                    for="{{$payment['uuid']}}_payment">--}}
{{--                                    <span class="list-item--label---column">--}}
{{--                                        @if($payment['uuid'] === session('payment_type', old('payment_type', null)))--}}
{{--                                            <i class="far fa-dot-circle"></i>--}}
{{--                                        @else <i class="far fa-circle"></i>--}}
{{--                                        @endif--}}
{{--                                        {!! $payment['name']  !!}--}}
{{--                                        @if($payment['info'])--}}
{{--                                            <br>--}}
{{--                                            <small> {{$payment['info']}} </small>--}}
{{--                                        @endif--}}
{{--                                    </span>--}}
{{--                                    <b class="list-item--label---column">{{$payment['price'] && ($cart['free_shipping'] >= 0 || !$cart['free_shipping_allowed']) ? $payment['price_formatted'] : $translations['cart.free_price']['text']}}</b>--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </form>--}}
{{--                    @if(session()->get('shipping_type') && session()->get('payment_type'))--}}
{{--                        <form--}}
{{--                            action="{{route(locale() . '.cart.continue_to_checkout')}}"--}}
{{--                            id="continue-to-checkout"--}}
{{--                            method="post">--}}
{{--                            @csrf--}}
{{--                            <div id="toc-error" class="hidden ml-4">--}}
{{--                                <small--}}
{{--                                    class="text-danger">{{$translations['cart.toc_shipping_and_payment_required']['text']}}</small>--}}
{{--                            </div>--}}
{{--                            <div class="">--}}
{{--                                <input type="hidden" name="toc" value="0">--}}
{{--                                <input class="ml-4 mr-2" id="toc"--}}
{{--                                       name="toc" type="checkbox" value="1"--}}
{{--                                       @if(old('toc', false)) checked="checked" @endif>--}}
{{--                                <label class=""--}}
{{--                                       for="toc"><small>{{$translations['cart.gdpr_accept_start']['text']}}--}}
{{--                                        <a class="text-secondary"--}}
{{--                                           target="_blank"--}}
{{--                                           href="{{$documentSettings['toc_document']['value']}}"><b>{{$translations['cart.gdpr_accept_toc']['text']}}</b></a> {{$translations['cart.gdpr_accept_middle']['text']}}--}}
{{--                                        <a--}}
{{--                                            target="_blank"--}}
{{--                                            class="text-secondary"--}}
{{--                                            href="{{route(locale() . '.page', [$gdprPage['slug']])}}"><b>{{$translations['cart.gdpr_accept_self']['text']}}</b></a>.</small></label>--}}
{{--                            </div>--}}
{{--                            <div class="text-right">--}}
{{--                                <button type="button"--}}
{{--                                        onclick="submitShippingToc()"--}}
{{--                                        class="button button--primary button--inline mt-4">--}}
{{--                                    <span>{{$translations['cart.cta_continue_to_checkout']['text']}}</span>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </section>

@endsection

@section('js')
@endsection
