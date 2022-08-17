<section class="category-list">
    <div class="container">
        <div class="category-list--container">
            @foreach($categories as $categoryItem)
                <a href="{{route(locale() . '.product.category', [$categoryItem['slug']])}}" class="category-list--item @if($category && $categoryItem['slug'] === $category['slug']) active @endif">
                    <img src="{{$categoryItem['image_url']}}" alt="{{$categoryItem['name']}}">
                    <h3>{{$categoryItem['name']}}</h3>
                </a>
            @endforeach
            @foreach($categories as $categoryItem)
                <a href="{{route(locale() . '.product.category', [$categoryItem['slug']])}}" class="category-list--item @if($category && $categoryItem['slug'] === $category['slug']) active @endif">
                    <img src="{{$categoryItem['image_url']}}" alt="{{$categoryItem['name']}}">
                    <h3>{{$categoryItem['name']}}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>
