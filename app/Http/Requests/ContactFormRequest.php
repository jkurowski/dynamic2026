<?php

namespace App\Http\Requests;

use App\Rules\ReCaptchaV3;
use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email:rfc',
            'message' => 'required',
            'phone' => 'required',
            'g-recaptcha-response' => ['required', new ReCaptchaV3()]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'To pole jest wymagane',
            'email.required' => 'To pole jest wymagane',
            'email.email' => 'Nieprawidłowy adres e-mail',
            'message.required' => 'To pole jest wymagane',
            'phone.required' => 'To pole jest wymagane'
        ];
    }
}
