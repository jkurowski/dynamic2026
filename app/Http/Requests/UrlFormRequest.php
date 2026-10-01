<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UrlFormRequest extends FormRequest
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
            'active' => 'boolean',
            'parent_id' => 'integer',
            'title' => 'required|string|min:2|max:100',
            'title_text' => '',
            'url' => '',
            'url_target' => '',
            'content_header' => '',
            'file_header' => '',
            'meta_title' => '',
            'meta_description' => '',
            'meta_robots' => '',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'To pole jest wymagane',
            'title.max.string' => 'Maksymalna ilość znaków: 100',
            'title.min.string' => 'Minimalna ilość znaków: 5'
        ];
    }
}
