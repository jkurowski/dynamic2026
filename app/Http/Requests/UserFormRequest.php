<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserFormRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . ($this->route()->user ?? ''),
            'password' => $this->method() === 'PUT' ? 'nullable|same:confirm-password' : 'required|same:confirm-password',
        ];

        if (Auth::user()->hasRole('Administrator')) {
            $rules['roles'] = 'required';
        } else {
            $rules['roles'] = 'nullable'; // Non-admin users don't need to provide roles
        }

        return $rules;
    }
}
