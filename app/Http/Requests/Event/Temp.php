<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\ApiRequest;

class Temp extends ApiRequest
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
            'step' => 'required',
            'max' => 'required',
            'options' => 'required|array',
            'token' => 'required',
        ];
    }
}
