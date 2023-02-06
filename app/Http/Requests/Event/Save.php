<?php

namespace App\Http\Requests\Event;

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
            'name' => 'required|string',
            'type' => 'required|string',
            'from' => 'required',
            'to' => 'required',
            'riders' => 'required|numeric',
            'price' => 'required|numeric',
            'fee' => 'numeric',
            'regions' => 'required|array',
            'coach' => '',
        ];
    }
}
