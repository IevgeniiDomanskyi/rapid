<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Card\Item as CardItemResource;

class Customer extends JsonResource
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
            'booked_at' => ! empty($this->pivot->created_at) ? $this->pivot->created_at->format('d/m/Y') : '',
            'is_paid' => $this->pivot->is_paid,
            'is_bank' => $this->pivot->is_bank,
            'card' => new CardItemResource($this->card),
        ];
    }
}
