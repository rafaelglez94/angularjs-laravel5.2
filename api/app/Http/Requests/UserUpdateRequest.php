<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserUpdateRequest extends Request
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
            'name' => 'max:255',
            'username' => 'alpha_dash|exists:users',
            'image' => 'image|max:2048',
            'email' => 'email|max:255|exists:users',
            'password' => 'min:6|confirmed'
        ];
    }
    public function wantsJson()
    {
        return true;
    }

}
