<section class="category-list">
    <div class="container">
        <div class="category-list--container--wrapper">
            <div class="category-list--container" data-page="0">
                @foreach ($items as $categoryItem)
                    <a href="{{ \App\Http\Controllers\ProductController::buildCategoryRoute($categoryItem['slug']) }}"
                        class="category-list--item @if (isset($category) && ($categoryItem['slug'] === $category['slug'])) active @endif">
                        <div class="category-list--item--image">
                            @if($categoryItem['image_url'] != '-')
                                <img src="{{$categoryItem['image_url']}}" alt="{{$categoryItem['name']}}">
                            @endif
                        </div>
                        <h4>{{ $categoryItem['name'] }}</h4>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
