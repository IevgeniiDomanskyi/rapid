<?php

namespace App\Http\Requests\Coach;

use App\Http\Requests\ApiRequest;

class Save extends ApiRequest
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
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'regions' => 'required|array',
        ];
    }
}
