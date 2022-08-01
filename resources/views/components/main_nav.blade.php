@foreach($headerPages as $headerPage)
    <li>
        <a href="{{route(locale() . '.page', [$headerPage['slug']])}}">
            {{$headerPage['title']}}
        </a>
    </li>
@endforeach
