<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialFormRequest extends FormRequest
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
            'social_facebook' => 'max:255|nullable|url',
            'social_instagram' => 'max:255|nullable|url',
            'social_linkedin' => 'max:255|nullable|url',
            'social_youtube' => 'max:255|nullable|url',
            'social_twitter' => 'max:255|nullable|url',
            'social_vimeo' => 'max:255|nullable|url',
            'og_title' => 'max:90|nullable',
            'og_description' => 'max:200|nullable',
            'og_type' => 'max:200|nullable',
        ];
    }
}
