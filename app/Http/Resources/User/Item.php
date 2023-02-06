<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
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
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'gdpr' => $this->gdpr,
            'payment_request' => $this->payment_request,
        ];
    }
}
