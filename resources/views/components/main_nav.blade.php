@foreach($headerPages as $headerPage)
    <li>
        <a href="{{route(locale() . '.page', [$headerPage['slug']])}}" class="{{$headerPage['slug'] == $page->slug ? 'active' : ''}}">
            {{$headerPage['title']}}
        </a>
    </li>
@endforeach
