<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobFormRequest extends FormRequest
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
            'name' => 'required|string|min:5|max:100',
            'city' => 'string|min:5|max:100',
            'email' => 'string|min:5|max:120',
            'text' => 'required',
            'active' => ''
        ];
    }

    public function messages()
    {
        return [
            'text.required' => 'To pole jest wymagane',
            'name.required' => 'To pole jest wymagane',
            'name.max.string' => 'Maksymalna ilość znaków: 100',
            'name.min.string' => 'Minimalna ilość znaków: 5',
            'city.required' => 'To pole jest wymagane',
            'city.max.string' => 'Maksymalna ilość znaków: 100',
            'city.min.string' => 'Minimalna ilość znaków: 5',
            'email.required' => 'To pole jest wymagane',
            'email.max.string' => 'Maksymalna ilość znaków: 120',
            'email.min.string' => 'Minimalna ilość znaków: 5'
        ];
    }
}
