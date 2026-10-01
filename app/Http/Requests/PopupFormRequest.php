<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PopupFormRequest extends FormRequest
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
            'popup_status' => 'boolean',
            'popup_mode' => 'boolean',
            'popup_timeout' => 'required|integer',
            'popup_text' => '',
        ];
    }
}
