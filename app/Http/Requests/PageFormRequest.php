<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PageFormRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title, '-')
        ]);
    }

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
        if ($this->request->get('_method') == 'PUT') {
            return [
                'active' => 'boolean',
                'parent_id' => 'integer|nullable',
                'title' => 'required|string|min:2|max:100|unique:pages,id,' . $this->route()->page,
                'title_text' => '',
                'content' => 'required|string|min:5',
                'content_header' => '',
                'file_header' => '',
                'meta_title' => '',
                'meta_description' => '',
                'meta_robots' => '',
            ];
        } else {
            return [
                'active' => 'boolean',
                'parent_id' => 'integer|nullable',
                'title' => 'required|string|min:2|max:100|unique:pages',
                'title_text' => '',
                'content' => 'required|string|min:5',
                'content_header' => '',
                'file_header' => '',
                'meta_title' => '',
                'meta_description' => '',
                'meta_robots' => '',
            ];
        }
    }

    public function messages()
    {
        return [
            'title.required' => 'To pole jest wymagane',
            'title.unique' => 'Taka strona już istnieje',
            'title.max.string' => 'Maksymalna ilość znaków: 100',
            'title.min.string' => 'Minimalna ilość znaków: 5',
            'content.required' => 'To pole jest wymagane',
        ];
    }
}
