<?php

namespace App\Http\Requests\Certificate;

use App\Http\Requests\ApiRequest;

class Send extends ApiRequest
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
            'book_id' => 'required|numeric',
            'id' => 'required',
            'prevCourse' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'moto' => '',
            'coach' => 'required',
            'course' => 'required',
            'summary' => '',
            'areas' => '',
            'comments' => '',
            'date' => 'required',
            'road' => '',
            'planning' => '',
            'control' => '',
            'general' => '',
            'cornering' => '',
            'urban' => '',
            'overtalking' => '',
        ];
    }
}
