<section class="category-list">
    <div class="container">
        <div class="category-list--container--wrapper">
            <div class="category-list--container" data-page="0">
                @foreach ($categories as $categoryItem)
                    <a href="{{ \App\Http\Controllers\ProductController::buildCategoryRoute($categoryItem['slug']) }}"
                        class="category-list--item @if (isset($category) && ($categoryItem['slug'] === $category['slug'])) active @endif">
                        <div class="category-list--item--image">
                            @if($categoryItem['image_url'] != '-')
                                <img src="{{$categoryItem['image_url']}}" alt="{{$categoryItem['name']}}">
                            @endif
                        </div>
                        <h3>{{ $categoryItem['name'] }}</h3>
                    </a>
                @endforeach
            </div>
            <div class="category-list--arrow-left">
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
                </svg>
            </div>
            <div class="category-list--arrow-right">
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" />
                </svg>
            </div>
        </div>
    </div>
</section>
