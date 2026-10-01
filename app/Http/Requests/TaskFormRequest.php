<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskFormRequest extends FormRequest
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
            'client_id' => 'nullable|integer',
            'stage_id' => 'nullable|integer',
            'board_id' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Wpisz <b>nazwę</b> zadania'
        ];
    }
}
