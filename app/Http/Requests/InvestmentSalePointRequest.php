<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvestmentSalePointRequest extends FormRequest
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
    public function rules()
    {
        return [
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'building_number' => 'required|string|max:50',
            'apartment_number' => 'nullable|string|max:50',
            'postal_code' => 'required|regex:/^\d{2}-\d{3}$/',
            'additional_locations' => 'nullable|string',
            'contact_method' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'province.required' => 'Województwo jest wymagane.',
            'district.required' => 'Powiat jest wymagany.',
            'municipality.required' => 'Gmina jest wymagana.',
            'city.required' => 'Miejscowość jest wymagana.',
            'building_number.required' => 'Nr budynku jest wymagany.',
            'postal_code.required' => 'Kod pocztowy jest wymagany.',
            'postal_code.regex' => 'Kod pocztowy musi być w formacie 00-000.',
        ];
    }
}
