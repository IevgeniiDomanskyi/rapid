<?php

namespace App\Http\Requests\Address;

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
            'line1' => 'nullable|string',
            'line2' => 'nullable|string',
            'town' => 'nullable|string',
            'city' => 'nullable|string',
            'county' => 'nullable|string',
            'country' => 'nullable|string',
            'postcode' => 'nullable|string',
        ];
    }
}
