<a href="{{route(locale() . '.product.category', [$item['slug']])}}" class="category-box">
    <img src="{{$item['image_url']}}" alt="{{$item['name']}}">
    <h4 class="category-box--name">
        {{$item['name']}}
    </h4>
</a>
