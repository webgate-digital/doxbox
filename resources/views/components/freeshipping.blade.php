<div class="alert alert--primary" id="free-shipping-bar" style="@if(isset($cart['free_shipping']) && $cart['free_shipping_allowed'] && $cart['free_shipping'] >= 0) display: block; @else display: none; @endif">
    {!! __($translations['cart.free_shipping']['text'], ['price' => '<span id="free-shipping-bar-price">' . $cart['free_shipping_formatted'] . '</span>']) !!}
</div>
