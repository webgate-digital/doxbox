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
        </div>
    </section>

@endsection

@section('js')
    <script src="https://widget.packeta.com/v6/www/js/library.js"></script>
@endsection
