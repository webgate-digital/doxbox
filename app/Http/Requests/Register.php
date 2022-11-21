<?php

namespace App\Http\Requests;

use App\Repositories\TranslationRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class Register
    extends FormRequest
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
            'name'                  => ['required', 'string', 'min:5'],
            'email'                 => ['required', 'email:rfc,dns'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages()
    {
        $translations = Cache::rememberForever('translations_validation', function () {
            $_translationRepository = new TranslationRepository();

            return $_translationRepository->validation(locale())['items'];
        });

        return [
            'email'     => $translations['email']['text'],
            'required'  => $translations['required']['text'],
            'min'       => $translations['min.string']['text'],
            'confirmed' => $translations['confirmed']['text'],
            'string'    => $translations['string']['text'],

        ];
    }
}
