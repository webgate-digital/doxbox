<div class="cart-steps">
    <div class="container">
        <div class="flex -mx-8">
            <a href="{{route(locale() . '.cart')}}" class="cart-step @if(Route::currentRouteName() === locale() . '.cart') cart-step--active @endif">
                <div class="cart-step--number">
                    1
                </div>
                <div class="">{{$translations['cart.title']['text']}}</div>
            </a>
            <a href="{{route(locale() . '.cart.shipping_and_payment')}}" class="cart-step @if(Route::currentRouteName() === locale() . '.cart.shipping_and_payment') cart-step--active @endif">
                <div class="cart-step--number">
                    2
                </div>
                <div class="">{{$translations['cart.shipping_and_payment_title']['text']}}</div>
            </a>
            <a href="{{route(locale() . '.cart.checkout')}}" class="cart-step @if(Route::currentRouteName() === locale() . '.cart.checkout') cart-step--active @endif">
                <div class="cart-step--number">
                    3
                </div>
                <div class="">{{$translations['cart.checkout_title']['text']}}</div>
            </a>
        </div>
    </div>
</div>
