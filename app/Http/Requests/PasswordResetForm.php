<?php

namespace App\Http\Requests;

use App\Repositories\TranslationRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class PasswordResetForm extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255'],
            'token' => ['required', 'string'],
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
            'string' => $translations['string']['text'],
            'required' => $translations['required']['text'],
            'max' => $translations['max.string']['text'],
        ];
    }
}
