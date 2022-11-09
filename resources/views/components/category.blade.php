<a href="{{route(locale() . '.product.category', [$item['slug']])}}" class="category-box">
    <div class="category-box--image">
        @if($item['image_url'] != '-')
            <img src="{{$item['image_url']}}" alt="{{$item['name']}}">
        @endif
    </div>
    <h4 class="category-box--name">
        {{$item['name']}}
    </h4>
</a>
