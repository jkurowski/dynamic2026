<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class IssueFormRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::id(),
            'property_id' => (int) $this->input('property_id'),
            'storage_id' => (int) $this->input('storage_id'),
            'parking_id' => (int) $this->input('parking_id'),
        ]);

        if ($this->input('property_id') === 0) {
            $this->request->remove('property_id');
        }

        if ($this->input('parking_id') === 0) {
            $this->request->remove('parking_id');
        }

        if ($this->input('storage_id') === 0) {
            $this->request->remove('storage_id');
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:100',
            'status' => 'required|integer',
            'type' => 'required|integer',
            'department_id' => 'required|integer',
            'note' => '',
            'start' => 'required|date|date_format:Y-m-d',
            'investment_id' => 'required|integer|exists:investments,id',

            'property_id' => 'nullable|integer',
            'parking_id' => 'nullable|integer',
            'storage_id' => 'nullable|integer',

            'contact_id' => 'required|integer|exists:clients,id',
            'user_id' => ''
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 400));

    }

    public function messages()
    {
        return [
            'title.required' => 'Wpisz <b>nazwę</b> zgłoszenia.',
            'contact_id.exists' => '<b>Zgłaszający</b> nie istnieje w bazie klientów.',
            'investment_id.exists' => 'Wybierz <b>inwestycję</b> z listy.',
            'start.required' => 'Pole <b>data</b> nie może być puste',
            'start.date' => 'Pole <b>data</b> ma zły format daty',
        ];
    }
}
