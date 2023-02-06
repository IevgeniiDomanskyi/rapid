<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class Profile extends ApiRequest
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
            'email' => 'required|email|unique:users,email,'.auth()->id(),
            'dob' => 'nullable|date',
            'exp' => 'numeric',
            'gdpr' => 'required|boolean',
            'link' => 'regex:/{hash}/',
        ];
    }
}
