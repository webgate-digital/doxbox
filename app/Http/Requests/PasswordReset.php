<?php

namespace App\Http\Requests;

use App\Repositories\TranslationRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class PasswordReset extends FormRequest
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
            'email' => ['required', 'string', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'password_confirmation' => ['required', 'string']
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
            'min' => $translations['min.string']['text'],
            'max' => $translations['max.string']['text'],
            'confirmed' => $translations['confirmed']['text'],
        ];
    }
}
