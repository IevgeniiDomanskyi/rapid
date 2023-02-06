<?php

namespace App\Http\Requests\Book;

use App\Http\Requests\ApiRequest;

class TrackDateDefine extends ApiRequest
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
            'book' => 'required|numeric',
            'date' => 'required|numeric',
        ];
    }
}
