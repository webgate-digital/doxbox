<section class="breadcrumbs">
    <div class="container">
        <div class="flex items-center flex-nowrap" id="navigationBar">
            <div class="breadcrumbs--item" id="ellipsis" style="display: none;">
                ...
            </div>
            <div class="breadcrumbs--item breadcrumbs--item-separator" id="ellipsisSeparator" style="display: none;">
                <img src="{{asset('images/icons/navigate_next_black_24dp.svg')}}" width="15">
            </div>
            <div class="breadcrumbs--item">
                <a href="{{route(locale() . '.homepage')}}">{{$translations['breadcrumbs.home']['text']}}</a>
            </div>
            @foreach($breadcrumbs as $breadcrumb)
                <div class="breadcrumbs--item breadcrumbs--item-separator">
                    <img src="{{asset('images/icons/navigate_next_black_24dp.svg')}}" width="15" alt="{{$breadcrumb['title']}}">
                </div>
                <div class="breadcrumbs--item">
                    @if(isset($breadcrumb['url']))
                        <a href="{{$breadcrumb['url']}}">{{$breadcrumb['title']}}</a>
                    @else
                        {{$breadcrumb['title']}}
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
