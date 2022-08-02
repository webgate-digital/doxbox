@foreach($headerNavigationItems as $item)

    <li class="{{isset($categorySlug) && $categorySlug == $item['slug'] ? 'active' : ''}}">
        <a href="{{route(locale() . '.product.category', [$item['slug']])}}">
            {{$item['name']}}
        </a>
    </li>
@endforeach
