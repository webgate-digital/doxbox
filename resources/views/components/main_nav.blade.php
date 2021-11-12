<li>
    <a href="{{route(locale() . '.product.list')}}">{{$translations['menu.products']['text']}}</a>
</li>
@foreach($headerPages as $headerPage)
    <li>
        <a href="{{route(locale() . '.page', [$headerPage['slug']])}}">{{$headerPage['title']}}
        </a>
    </li>
@endforeach
{{--                            <li>--}}
{{--                                <a href="{{route(locale() . '.blog.list')}}">{{$translations['menu.blog']['text']}}</a>--}}
{{--                            </li>--}}
<li>
    <a href="{{route(locale() . '.contact')}}">{{$translations['menu.contact']['text']}}</a>
</li>
