<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\ApiRequest;

class Update extends ApiRequest
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
            'firstname' => 'string',
            'lastname' => 'string',
            'email' => 'string',
            'phone' => 'string',
            'dob' => 'date',
            'exp' => 'numeric',
        ];
    }
}
