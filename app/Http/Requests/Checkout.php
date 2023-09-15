<?php

namespace App\Http\Requests;

use App\Repositories\TranslationRepository;
use Cache;
use Illuminate\Foundation\Http\FormRequest;

class Checkout extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required',
            'street' => 'required',
            'house_number' => 'required|regex:~[0-9]~',
            'city' => 'required',
            'zip' => 'required',
            'state' => 'nullable',
            'country' => 'required',
            'country_code' => 'required',

            'shipping_name' => 'nullable|required_with:shipping_street,shipping_house_number,shipping_city,shipping_zip,shipping_state',
            'shipping_street' => 'nullable|required_with:shipping_name,shipping_house_number,shipping_city,shipping_zip,shipping_state',
            'shipping_house_number' => 'nullable|regex:~[0-9]~|required_with:shipping_name,shipping_street,shipping_city,shipping_zip,shipping_state',
            'shipping_city' => 'nullable|required_with:shipping_name,shipping_street,shipping_house_number,shipping_zip,shipping_state',
            'shipping_zip' => 'nullable|required_with:shipping_name,shipping_street,shipping_house_number,shipping_city,shipping_state',
            'shipping_state' => 'nullable',
            'shipping_country' => 'nullable|required_with:shipping_name,shipping_street,shipping_house_number,shipping_city,shipping_zip,shipping_state',

            'company_name' => 'nullable|required_with:company_id,company_tax_id,company_vat_id,company_address,company_city,company_zip,company_state,company_country',
            'company_id' => 'nullable|required_with:company_name,company_tax_id,company_vat_id,company_address,company_city,company_zip,company_state,company_country',
            'company_tax_id' => 'nullable|required_with:company_name,company_id,company_vat_id,company_address,company_city,company_zip,company_state,company_country',
            'company_vat_id' => 'nullable|vat_number|vat_number_exist|vat_number_format',
            'company_address' => 'nullable|required_with:company_name,company_id,company_tax_id,company_vat_id,company_city,company_zip,company_state,company_country',
            'company_city' => 'nullable|required_with:company_name,company_id,company_tax_id,company_vat_id,company_address,company_zip,company_state,company_country',
            'company_zip' => 'nullable|required_with:company_name,company_id,company_tax_id,company_vat_id,company_address,company_city,company_state,company_country',
            'company_state' => 'nullable',
            'company_country' => 'nullable|required_with:company_name,company_id,company_tax_id,company_vat_id,company_address,company_city,company_zip,company_state',

            'notes' => 'nullable',

            'toc' => 'accepted',
            'newsletter' => 'boolean',
            'heureka_allowed' => 'boolean',
        ];
    }

    public function messages()
    {
        $translations = Cache::rememberForever(locale() . '_translations_validation', function () {
            $_translationRepository = new TranslationRepository();
            return $_translationRepository->validation(locale())['items'];
        });

        return [
            'accepted' => $translations['accepted']['text'],
            'regex' => $translations['regex']['text'],
            'email' => $translations['email']['text'],
            'required' => $translations['required']['text'],
            'required_with' => $translations['required_with']['text'],
            'vat_number' => $translations['vat_number']['text'],
            'vat_number_format' => $translations['vat_number_format']['text'],
            'vat_number_exist' => $translations['vat_number_exist']['text'],
        ];
    }
}
