<section class="breadcrumbs">
    <div class="container">
        <div class="flex items-center flex-wrap">
            <div class="breadcrumbs--item">
                <a href="{{route(locale() . '.homepage')}}">{{$translations['breadcrumbs.home']['text']}}</a>
            </div>
            @foreach($breadcrumbs as $breadcrumb)
                <div class="breadcrumbs--item">
                    <img src="{{asset('images/icons/navigate_next_black_24dp.svg')}}" width="15" alt="{{$breadcrumb['title']}}">
                </div>
                <div class="breadcrumbs--item">
                    <a href="{{$breadcrumb['url']}}">{{$breadcrumb['title']}}</a>
                </div>
            @endforeach
        </div>
    </div>
</section>
