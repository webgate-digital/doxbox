<a href="{{route(locale() . '.product.category', [$item['slug']])}}" class="category-box">
    <div class="category-box--image">
        <img src="{{$item['image_url']}}" alt="{{$item['name']}}">
    </div>
    <h4 class="category-box--name">
        {{$item['name']}}
    </h4>
</a>
