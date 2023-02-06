<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\ApiRequest;

class BillingAddress extends ApiRequest
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
            'address_1' => 'string',
            'address_2' => 'string',
            'city' => 'string',
            'postcode' => 'string',
            'country' => 'numeric',
        ];
    }
}
