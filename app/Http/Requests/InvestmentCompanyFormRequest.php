<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvestmentCompanyFormRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'legal_form' => 'nullable|string|max:255',
            'krs_number' => 'nullable|string|max:20',
            'ceidg_number' => 'nullable|string|max:20',
            'nip' => 'nullable|string|max:20',
            'regon' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'fax' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'building_number' => 'nullable|string|max:20',
            'apartment_number' => 'nullable|string|max:20',
            'postal_code' => 'nullable|regex:/^\d{2}-\d{3}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Pole „Nazwa dewelopera” jest wymagane.',
            'name.max' => 'Nazwa nie może być dłuższa niż 255 znaków.',
            'email.email' => 'Adres e-mail musi być poprawnym adresem.',
            'website.url' => 'Adres strony musi być poprawnym adresem URL.',
            'postal_code.regex' => 'Kod pocztowy musi być w formacie 00-000.',
        ];
    }
}
