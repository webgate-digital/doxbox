@extends('layout', ['ogTitle' => $translations['cart.title']['text']])

@section('content')

    @if($cart['count'])
        @include('components.freeshipping')
    @endif

    @if($errors->any())
        <div class="alert alert--danger">
            {{$translations['general.form_validation_error']['text']}}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert--danger">
            {{session('error')}}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert--success">
            {{session('success')}}
        </div>
    @endif

    @include('components.cart_steps')

    <section class="section">
        <div class="container">
            @if($cart['count'])
                <h1 class="h1">
                    {{$translations['cart.title']['text']}}
                </h1>
                <cart-page
                    product-url="{{route(locale() . '.product.detail', ['CATEGORY', 'SLUG'])}}"
                    :translations="{{json_encode(['Na sklade nie je dostatočný počet kusov.' => $translations['cart.count_error']['text'], 'ks' => $translations['ks']['text'], 'Spolu' => $translations['cart.total']['text']])}}"
                ></cart-page>

                <div class="flex flex-wrap items-end my-16">
                    <div class="w-full lg:w-1/2">
                        @if($cart['voucher'])
                            <p>{{$translations['cart.voucher_code_active']['text']}}: <b>{{$cart['voucher']['code']}} -({{$cart['discount']['value_formatted']}})</b>
                                <a
                                    href="javascript:void(0);" class="text-danger flex text-medium"
                                    onclick="document.getElementById('remove-voucher-form').submit();">{{$translations['cart.voucher_cta_delete']['text']}}
                                    <img src="{{asset('images/icons/close_black_24dp.svg')}}" width="15" class="ml-4" alt="{{$translations['cart.voucher_cta_delete']['text']}}"> </a></p>
                            <form action="{{route(locale() . '.remove_voucher')}}" id="remove-voucher-form"
                                  method="post">
                                @csrf
                                @method('delete')
                            </form>
                        @endif
                        <form action="{{route(locale() . '.apply_voucher')}}" method="post">
                            @csrf
                            <div class="flex flex-wrap items-start justify-center">
                                <div class="w-full lg:w-3/4">
                                    <input type="text" class="form--input"
                                           name="voucher_code" value="{{old('voucher_code')}}"
                                           placeholder="{{$translations['cart.voucher_apply_placeholder']['text']}}">
                                    @error('voucher_code')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="w-full lg:w-1/4">
                                    <button type="submit" class="button button--ghost lg:ml-4">{{$translations['cart.voucher_cta_apply']['text']}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="w-full lg:w-1/2 flex flex-col items-end">
                        <h4 class="h4 mt-16 lg:mt-0">{{$translations['cart.total']['text']}}: <span
                                id="total-price-cart-formatted">{{$cart['price_formatted']}}</span></h4>
                        <a href="{{route(locale() . '.cart.shipping')}}"
                           class="button button--primary button--inline">
                            <span>{{$translations['cart.shipping_and_payment_title']['text']}}</span></a>
                    </div>
                </div>

                @if(count($upsell))
                    <h2 class="h2">
                        {{$translations['cart.upsell_title']['text']}}
                    </h2>
                    <div class="product-container">
                        @foreach($upsell as $product)
                            <div class="w-1/2 lg:w-1/4">
                                @include('components.product', ['item' => $product])
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <h1 class="h1">
                    {{$translations['cart.empty_title']['text']}}
                </h1>
                <a href="{{route(locale() . '.product.list')}}"
                   class="button button--primary button--inline">{{$translations['cart.cta_continue']['text']}}</a>
            @endif
        </div>
    </section>

@endsection
