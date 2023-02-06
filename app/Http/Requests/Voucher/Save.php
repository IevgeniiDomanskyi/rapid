<?php

namespace App\Http\Requests\Voucher;

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
            'type' => 'required',
            'amount' => 'required',
            'code' => 'required',
            'expired_at' => '',
            'excluded' => '',
            'coupon_limit' => '',
            'user_limit' => '',
        ];
    }
}
