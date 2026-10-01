<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ArticleFormRequest extends FormRequest
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
            'title' => 'required|string|min:5|max:100|unique:articles,title,'.$this->route()->article,
            'content_entry' => 'required|string|min:5',
            'content' => 'required|string|min:5',
            'posted_at' => 'nullable|date|date_format:Y-m-d',
            'status' => 'boolean',
            'file_alt' => '',
            'meta_title' => '',
            'meta_description' => '',
            'meta_robots' => ''
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'To pole jest wymagane',
            'title.unique' => 'Taki wpis już istnieje',
            'title.max.string' => 'Maksymalna ilość znaków: 100',
            'title.min.string' => 'Minimalna ilość znaków: 5',
            'content_entry.required' => 'To pole jest wymagane',
            'content.required' => 'To pole jest wymagane',
        ];
    }
}
