<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvestPageFormRequest extends FormRequest
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
            'investment_id' => 'integer',
            'active' => 'boolean',
            'contact_form' => 'boolean',
            'title' => 'required|string|min:2|max:190',
            'content' => 'required|string|min:5',
            'meta_title' => '',
            'meta_description' => '',
            'meta_robots' => '',
            'cta_text' => '',
            'cta_button' => '',
            'cta_link' => ''
        ];
    }
}
