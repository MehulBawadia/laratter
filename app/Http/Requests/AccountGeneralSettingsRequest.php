<?php

namespace Laratter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountGeneralSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() == true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'username' => 'required|alpha_dash|max:50|unique:users,username,'. auth()->id(),
            'email' => 'required|email|unique:users,email,'.auth()->id(),
        ];
    }
}
