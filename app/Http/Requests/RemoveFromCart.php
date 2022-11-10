<?php

namespace App\Http\Requests;

use App\Repositories\TranslationRepository;
use Cache;
use Illuminate\Foundation\Http\FormRequest;

class RemoveFromCart extends FormRequest
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
        return [
            'uuid' => 'required',
        ];
    }

    public function messages()
    {
        $translations = Cache::rememberForever(locale() . '_translations_validation', function () {
            $_translationRepository = new TranslationRepository();
            return $_translationRepository->validation(locale())['items'];
        });

        return [
            'required' => $translations['required']['text'],
        ];
    }
}
