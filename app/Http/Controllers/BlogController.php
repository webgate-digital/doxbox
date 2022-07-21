<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Repositories\BlogArticleRepository;

class BlogController
{
    private $_blogArticleRepository;

    public function __construct(BlogArticleRepository $blogArticleRepository)
    {
        $this->_blogArticleRepository = $blogArticleRepository;
    }

    public function list()
    {
        $items = $this->_blogArticleRepository->list(locale())['items'];

        return view('blog.list', compact('items'));
    }

    public function category(string $categorySlug)
    {
        try {
            $category = $this->_blogArticleRepository->category(locale(), session()->get('currency'), $categorySlug)['item'];
        } catch (NotFoundException $e) {
            abort(404);
        }

        return view('blog.category', compact('category'));
    }

    public function detail(string $categorySlug, string $slug)
    {
        try {
            $item = $this->_blogArticleRepository->detail(locale(), session()->get('currency'), $slug)['item'];
        } catch (NotFoundException $e) {
            abort(404);
        }

        return view('blog.detail', compact('item'));
    }
}
