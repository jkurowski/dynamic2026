<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatFormRequest extends FormRequest
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
            'message' => 'required|string|min:3',
            'id' => 'required|integer'
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
            'id.required' => 'Brakuje ID wiadomości, zamknij to okno i spróbuj ponownie',
            'message.required' => 'Wpisz treść wiadomości',
            'message.min.string' => 'Minimalna ilość znaków: 5'
        ];
    }
}
