@foreach ($products as $product)
    @include('components.product', ['item' => $product])
@endforeach
