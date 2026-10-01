<?php

namespace App\Http\Requests\Api;

use App\Models\Investment;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PostEventRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            'property_id' => (int) $this->input('property_id'),
            'storage_id' => (int) $this->input('storage_id'),
            'parking_id' => (int) $this->input('parking_id'),
        ]);
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
            'start' => 'required|date|date_format:Y-m-d',
            'time' => 'nullable|date_format:H:i',
            'client_id' => 'nullable|integer',
            'user_id' => 'nullable|integer|exists:users,id',
            //'investment_id' => "nullable|integer|exists:investments,id",
            'investment_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value != 0) {
                        // Check if the investment_id exists in the investments table
                        if (!Investment::where('id', $value)->exists()) {
                            $fail('The selected investment id is invalid.');
                        }
                    }
                },
            ],
            'department_id' => 'nullable|integer',
            'property_id' => 'nullable|integer',
            'storage_id' =>  'nullable|integer',
            'parking_id' =>  'nullable|integer',

            'type' => 'required|integer',
            'note' => '',
            'active' => 'boolean',
            'allday' => 'boolean'
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
    public function messages()
    {
        return [
            'name.required' => 'Wpisz <b>nazwę</b> wydarzenia',
            'start.required' => 'Pole <b>data</b> jest wymagane',
            'start.date_format' => 'Zły format daty w polu <b>Data</b>'
        ];
    }
}
