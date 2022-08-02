@foreach($headerNavigationItems as $item)

    <li class="{{$categorySlug == $item['slug'] ? 'active' : ''}}">
        <a href="{{route(locale() . '.product.category', [$item['slug']])}}">
            {{$item['name']}}
        </a>
    </li>
@endforeach
