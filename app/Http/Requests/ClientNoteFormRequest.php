<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientNoteFormRequest extends FormRequest
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
            'text' => 'required|string|min:5'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'text.required' => 'Wpisz treść notatki',
            'text.min.string' => 'Minimalna ilość znaków: 5'
        ];
    }
}
