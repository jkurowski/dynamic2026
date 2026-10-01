<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class EventsRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'start' => 'required|date_format:Y-m-d\TH:i:sP',
            'end' => 'required|date_format:Y-m-d\TH:i:sP',
            'client_id' => 'nullable|integer',
            'user_id' => 'nullable|integer'
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'start.required' => 'Parametr start jest wymagany',
            'start.date' => 'Zły format daty',
            'end.required' => 'Parametr start jest wymagany',
            'end.date' => 'Zły format daty',
            'client_id.integer' => 'Pole user client_id musi być liczbą całkowitą.',
            'user_id.integer' => 'Pole user user_id musi być liczbą całkowitą.',
        ];
    }
}
