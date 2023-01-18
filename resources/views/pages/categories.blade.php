@extends('layout', [])

@section('content')
    <section class="section">
        <div class="container">
            <h2 class="text-heading-2xs md:mb-20 mb-6">
                {{$translations['categories.title']['text']}}
            </h2>
            <div class="category-container">
                @foreach($categories as $category)
                    @include('components.category', ['item' => $category])
                @endforeach
            </div>
        </div>
    </section>
@endsection
