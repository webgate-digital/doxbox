<section class="category-list">
    <div class="container">
        <div class="category-list--container">
            @foreach($categories as $categoryItem)
                <div class="w-full lg:w-1/4">
                    <a href="{{route(locale() . '.product.category', [$categoryItem['slug']])}}" class="category-list--item @if($category && $categoryItem['slug'] === $category['slug']) active @endif">
                        <img src="{{$categoryItem['image_url']}}" alt="{{$categoryItem['name']}}">
                        <h3>{{$categoryItem['name']}}</h3>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
