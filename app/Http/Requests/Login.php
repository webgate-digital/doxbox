<?php

namespace App\Http\Requests;

use Cache;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\TranslationRepository;

class Login extends FormRequest
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
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        $translations = Cache::rememberForever('translations_validation', function () {
            $_translationRepository = new TranslationRepository();
            return $_translationRepository->validation(locale())['items'];
        });

        return [
            'email' => $translations['email']['text'],
            'required' => $translations['required']['text'],
        ];
    }
}
