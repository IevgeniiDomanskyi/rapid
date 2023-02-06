<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class Book extends ApiRequest
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
            'phone' => '',
            'email' => 'required|email',
            'password' => '',
            'confirm' => '',
            'link' => '',
        ];
    }
}
