<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Miasta / biura sprzedaży (boksy na stronie Kontakt, zakładki biur w sekcji kontaktu, mapa biur).
 */
class CityFormRequest extends FormRequest
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

    public function rules()
    {
        $rules = [
            'name' => 'required|string|min:2|max:100',
        ];

        // Pola nietłumaczone - tylko w wersji podstawowej (pl), jak w pozostałych modułach
        if (!$this->filled('lang') || $this->input('lang') === 'pl') {
            $rules += [
                'active' => 'required|boolean',
                'address' => 'nullable|string|max:2000',
                'working_hours' => 'nullable|string|max:2000',
                'phone' => 'nullable|string|max:2000',
                'map_link' => 'nullable|url|max:500',
                'file' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
                'lat' => 'nullable|numeric|between:-90,90',
                'lng' => 'nullable|numeric|between:-180,180',
                'sort' => 'nullable|integer|min:0',
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Podaj nazwę.',
            'map_link.url' => 'Link do Google Maps musi być pełnym adresem (https://...).',
            'file.image' => 'Kod QR musi być obrazkiem (PNG, JPG, WebP).',
            'lat.numeric' => 'Szerokość geograficzna to liczba, np. 52.1970523.',
            'lng.numeric' => 'Długość geograficzna to liczba, np. 21.0463495.',
        ];
    }
}
