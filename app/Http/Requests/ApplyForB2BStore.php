<?php

namespace App\Http\Requests;

use App\Repositories\TranslationRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class ApplyForB2BStore
    extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return session()->has('me') && !(session('me')['pending_b2b_approval'] || session('me')['b2b_approved']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name'    => ['required', 'string', 'max:255'],
            'company_id'      => ['required', 'string', 'max:255'],
            'company_tax_id'  => ['required', 'string', 'max:255'],
            'company_vat_id'  => ['nullable', 'string', 'max:255', 'vat_number','vat_number_exist','vat_number_format'],
            'company_address' => ['required', 'string', 'max:255'],
            'company_city'    => ['required', 'string', 'max:255'],
            'company_zip'     => ['required', 'string', 'max:255'],
            'company_country' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        $translations = Cache::rememberForever('translations_validation', function () {
            $_translationRepository = new TranslationRepository();

            return $_translationRepository->validation(locale())['items'];
        });

        return [
            'required' => $translations['required']['text'],
            'string'   => $translations['string']['text'],
            'max'      => $translations['max.string']['text'],
        ];
    }
}
