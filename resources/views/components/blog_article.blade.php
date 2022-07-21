<!-- Post item-->
<div class="post-item border border-grey">
    <div class="post-image">
        <a href="{{route(locale() . '.blog.detail', [$item['category']['slug'], $item['slug']])}}">
            <img src="{{$item['thumbnail_image_url']}}" alt="{{$item['title']}}">
        </a>
    </div>
    <div class="post-item-description p-10">
        {{--            <span class="post-meta-date"><i class="fa fa-calendar"></i> {{\Carbon\Carbon::parse($item->created_at)->format('d.m.Y')}}</span>--}}
        {{--            <span class="post-meta-comments"><a href=""><i class="fa fa-comments-o"></i>33 Comments</a></span>--}}
        <p class="post-meta-category">
            <a href="{{route(locale() . '.blog.category', [$item['category']['slug']])}}">
                {{$item['category']['name']}}
            </a>
        </p>
        <h2 class="mb-6">
            <a class="text-h4 font-semibold !text-black"
               href="{{route(locale() . '.blog.detail', [$item['category']['slug'], $item['slug']])}}">
                {{$item['title']}}
            </a>
        </h2>
        <p class=" h-[104px]">{{\Illuminate\Support\Str::limit($item['perex'])}}</p>

        <a href="{{route(locale() . '.blog.detail', [$item['category']['slug'], $item['slug']])}}">
            {{$translations['blog.cta_read_more']['text']}} &raquo;
        </a>
    </div>
</div>
<!-- end: Post item-->
