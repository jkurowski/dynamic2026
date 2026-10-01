<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'due_date' => 'required|date',
            'paid_at' => [
                'nullable',
                'required_if:status,1',
                'date'
            ],
            'amount' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'percent' => 'required|integer',
            'property_id' => 'required|integer',
            'status' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'amount.regex' => 'Cena nie powinna zawierać przecinka',
            'paid_at.required_if' => 'Data wpłaty jest wymagana kiedy opcja Zapłacono: Tak ',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 400));
    }
}