<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyPriceComponentFormRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:property_price_components,name'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Pole „Nazwa” jest wymagane.',
            'name.string' => 'Pole „Nazwa” musi być tekstem.',
            'name.max' => 'Pole „Nazwa” nie może przekraczać 255 znaków.',
            'name.unique' => 'Składnik ceny o takiej nazwie już istnieje.',
        ];
    }
}
