<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class Filter extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Route::currentRouteName() === locale() . '.product.category') {
            // $this->redirect = \App\Http\Controllers\ProductController::buildCategoryRoute($this->categorySlug);
        } else {
            $this->redirect = route(locale() . '.product.list');
        }

        if ($this->has('sort') || $this->has('max_price') || $this->has('min_price') || $this->has('attributes')) {
            return [
                'sort' => 'required',
                'max_price' => 'required',
                'min_price' => 'required',
                'attributes' => 'nullable|array'
            ];
        }

        return [];
    }
}
