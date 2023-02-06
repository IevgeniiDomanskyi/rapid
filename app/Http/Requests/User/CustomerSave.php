<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiRequest;

class CustomerSave extends ApiRequest
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
            'email' => 'required|email|unique:users,email'.( ! empty(request()->route('customer')->id) ? ','.request()->route('customer')->id : ''),
            'phone' => 'required',
            'postcode' => '',
            'line1' => 'required',
            'line2' => '',
            'town' => 'required',
            'county' => '',
            'country' => 'required',
        ];
    }
}
