@foreach($headerNavigationItems as $item)
    <li>
        <a href="{{route(locale() . '.product.category', [$item['slug']])}}">
            {{$item['name']}}
        </a>
    </li>
@endforeach
