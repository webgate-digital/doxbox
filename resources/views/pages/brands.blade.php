@extends('layout', [])

@section('content')
    <section class="section">
        <div class="container">
            <h2 class="text-heading-2xs md:mb-20 mb-6">
                {{$translations['brands.title']['text']}}
            </h2>
            <div class="category-container">
                @foreach($brands as $brand)
                    @include('components.brand', ['item' => $brand])
                @endforeach
            </div>
        </div>
    </section>
@endsection
