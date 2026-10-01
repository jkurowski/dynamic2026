<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *      "client" => null
            "client_id" => "0"
            "client_name" => "aasda"
            "client_surname" => null
            "client_email" => "sdasdasd"
            "client_phone" => null
            "email_bcc" => null
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:180',
            'message' => 'required|string|min:5',
            'date_end' => 'nullable|date|date_format:Y-m-d',
            'email_bcc' => '',
            'client_phone' => '',
            'client_email' => 'required|email',
            'client_surname' => '',
            'client_name' => 'required',
            'client_id' => 'integer',
            'property' => 'array',
            'offer_template' => 'integer'
        ];
    }
}
