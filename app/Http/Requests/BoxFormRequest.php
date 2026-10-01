<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoxFormRequest extends FormRequest
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
            'title' => 'required|string|min:5|max:100',
            'text' => 'required|string|min:5|max:200',
            'place_id' => 'required',
            'file_alt' => '',
            'link' => '',
            'link_button' => '',
            'link_target' => '',
            'aos_animation' => '',
            'aos_duration' => '',
            'aos_delay' => '',
            'aos_offset' => '',
            'sort' => ''
        ];
    }
}
