<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class BillingAddress extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_profile_id' => $this->customer_profile_id,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'city' => $this->city,
            'postcode' => $this->postcode,
            'country' => $this->country,
        ];
    }
}
