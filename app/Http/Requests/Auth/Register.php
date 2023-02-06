<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class Register extends ApiRequest
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
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'confirm' => 'required|boolean',
            'gdpr' => 'required|boolean',
            'link' => 'required|regex:/{hash}/',
        ];
    }
}
