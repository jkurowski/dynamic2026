<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvestmentFormRequest extends FormRequest
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
            'type' => 'integer',
            'status' => 'integer',
            'progress' => 'integer',
            'name' => 'required|string|min:5|max:100',
            'address' => '',
            'city_id' => '',
            'gallery_id' => '',
            'date_start' => '',
            'date_end' => '',
            'areas_amount' => '',
            'area_range' => '',
            'floor_range' => '',
            'room_range' => '',
            'office_address' => '',
            'office_emails' => 'required',
            'meta_title' => '',
            'meta_description' => '',
            'meta_robots' => '',
            'gradient_thumb' => '',
            'gradient_header' => '',
            'entry_content' => '',
            'commercial' => '',
            'content' => '',
            'end_content' => '',
            'contact_content' => '',
            'menu' => '',
            'mockup' => '',
            'show_prices' => 'boolean',
            'show_properties' => 'integer',
            'users' => '',
            'supervisors' => '',
            'file_brochure' => '',

            'inv_province' => ['required', 'string', 'max:100'],
            'inv_county' => ['nullable', 'string', 'max:100'],
            'inv_municipality' => ['nullable', 'string', 'max:100'],
            'inv_city' => ['required', 'string', 'max:100'],
            'inv_street' => ['nullable', 'string', 'max:150'],
            'inv_property_number' => ['nullable', 'string', 'max:50'],
            'inv_postal_code' => ['nullable', 'string', 'max:20', 'regex:/^\d{2}-\d{3}$/'], // matches 00-000 format

            'company_id' => ['required', 'integer', 'exists:investment_companies,id'],
            'sale_point_id' => ['nullable', 'integer', 'exists:investment_sale_points,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'To pole jest wymagane',
            'name.max.string' => 'Maksymalna ilość znaków: 100',
            'name.min.string' => 'Minimalna ilość znaków: 5'
        ];
    }
}
