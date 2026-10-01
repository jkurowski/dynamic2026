<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ZeroOrExists;
use Illuminate\Validation\Rule;

class PropertyFormRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (!$this->filled('window')) {
            $this->merge(['window' => null]);
        }

        $this->merge([
            'investment_id' => $this->route('investment')->id
        ]);

        if (!$this->filled('floor')) {
            $this->merge([
                'floor_id' => $this->route('floor')->id
            ]);
        }

        if ($this->has('area')) {
            $this->merge([
                'area' => str_replace(',', '.', $this->input('area')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'investment_id' => [
                'required',
                'integer',
                Rule::exists('investments', 'id'), // Check if investment with the specified id exists
            ],
            'building_id' => 'nullable|integer',
            'floor_id' => 'nullable|integer',
            'status' => 'required',
            'name' => 'required|string|max:255',
            'name_list' => 'string|max:255',
            'number' => 'required|string|max:255',
            'number_order' => 'integer',
            'visitor_related_type' => 'integer',
            'visitor_related_ids' => 'required_if:visitor_related_type,3|array|min:1',
            'type' => 'required|integer',
            'rooms' => 'required|integer',
            'window' => 'sometimes|nullable',
            'area' => 'required|numeric|min:0',
            'area_search' => '',
            'garden_area' => '',
            'balcony_area' => '',
            'balcony_area_2' => '',
            'terrace_area' => '',
            'loggia_area' => '',
            'plot_area' => '',
            'parking_space' => '',
            'garage' => '',
            'price' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'price_brutto' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'price_30' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'vat' => '',
            'cords' => '',
            'html' => '',
            'meta_title' => '',
            'meta_description' => '',
            'active' => 'boolean',
            'promotion_price' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !$this->boolean('highlighted')) {
                        $fail('Pole "Promocja" musi być zaznaczone, jeśli ustawiono cenę promocyjną.');
                    }
                },
            ],
            'highlighted' => [
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($this->boolean($attribute) && empty($this->input('promotion_price'))) {
                        $fail('Pole "Cena promocyjna" jest wymagane, jeśli nieruchomość ma być promowana.');
                    }
                },
            ],
            'promotion_end_date' => 'nullable|date|after:now',
            'promotion_price_show' => 'boolean',
            'client_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value != 0) {
                        $exists = Client::where('id', $value)->exists();
                        if (!$exists) {
                            $fail('The selected client does not exist.');
                        }
                    }
                },
            ],
            'saled_at' => 'nullable|date',
            'reservation_until' => 'nullable|date|after_or_equal:saled_at',
            'text' => '',
            'history_info' => '',

            'price-component-type'     => 'array',
            'price-component-type.*'   => 'required|exists:property_price_components,id',

            'price-component-category'   => 'array',
            'price-component-category.*' => 'required|in:1,2',

            'price-component-value'     => 'array',
            'price-component-value.*'   => 'nullable',

            'price-component-m2-value'     => 'array',
            'price-component-m2-value.*'   => 'nullable',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'client_id.exists' => 'The selected client does not exist.',
            'saled_at.date' => 'The saled at must be a valid date.',
            'reservation_until.date' => 'The reservation until must be a valid date.',
            'reservation_until.after_or_equal' => 'The reservation until must be a date after or equal to the saled at date.',
            'price_brutto.numeric' => 'Pole "Cena brutto" musi być liczbą.',
            'price_brutto.regex' => 'Pole "Cena brutto" musi zawierać maksymalnie dwie cyfry po przecinku.',

            // Add these for your visitor_related_ids validation:
            'visitor_related_ids.required' => 'Musisz wybrać dodatkowe powierzchnie.',
            'visitor_related_ids.min' => 'Musisz wybrać przynajmniej jedną dodatkową powierzchnię.',
            'visitor_related_ids.array' => 'Nieprawidłowy format powierzchni dodatkowych.',
            'visitor_related_ids.required_if' => 'Pole "Powierzchnie dodatkowe" jest wymagane, gdy wybrano opcję "Tylko wybrane".',
        ];
    }
}
