<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Address\Item as AddressItemResource;
use App\Http\Resources\Postcode\Item as PostcodeItemResource;

class Me extends JsonResource
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
            'role' => $this->role,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'dob' => ! empty($this->dob) ? $this->dob->toDateString() : $this->dob,
            'phone' => $this->phone,
            'exp' => $this->exp,
            'gdpr' => $this->gdpr,
            'payment_request' => $this->payment_request,
            'postcodes' => PostcodeItemResource::collection($this->postcodes),
            $this->mergeWhen( ! empty($this->address), [
                'address' => $this->address,
            ]),
            $this->mergeWhen( ! empty($this->token), [
                'token' => $this->token,
            ]),
        ];
    }
}
