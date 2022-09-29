<div class="cart-steps">
    <div class="container">
        <div class="flex -mx-8">
            <a href="{{route(locale() . '.cart')}}"
               class="cart-step cart-step--active">
                <div class="cart-step--number">
                    @if(Route::currentRouteName() === locale() . '.cart.shipping' || Route::currentRouteName() === locale() . '.cart.checkout') @include('components.icons.done', ['colorClass' => 'text-primary']) @else
                        1 @endif
                </div>
                <div class="">{{$translations['cart.title']['text']}}</div>
            </a>
            <a href="{{route(locale() . '.cart.shipping')}}"
               class="cart-step @if($step > 1) cart-step--active @endif">
                <div class="cart-step--number">
                    @if(Route::currentRouteName() === locale() . '.cart.checkout') @include('components.icons.done', ['colorClass' => 'text-primary']) @else
                        2 @endif
                </div>
                <div class="">{{$translations['cart.shipping_and_payment_title']['text']}}</div>
            </a>
            <a href="{{route(locale() . '.cart.checkout')}}"
               class="cart-step @if($step > 2) cart-step--active @endif">
                <div class="cart-step--number">
                    3
                </div>
                <div class="">{{$translations['cart.checkout_title']['text']}}</div>
            </a>
        </div>
    </div>
</div>
