<?php

namespace App\Http\Resources\Event;

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
            'event_id' => $this->eventId(),
            'name' => $this->name,
            'type' => $this->type,
            'typeText' => ucfirst($this->type),
            'coach' => $this->coach,
            'region' => $this->region,
            'from' => $this->from->format('d/m/Y'),
            'to' => $this->to->format('d/m/Y'),
            'riders' => $this->riders,
            'left' => ($this->riders - $this->customers->count()),
            'customers' => $this->customers->count(),
            'price' => $this->price,
            'fee' => $this->fee,
            'booked_at' => ! empty($this->pivot->created_at) ? $this->pivot->created_at->format('d/m/Y') : '',
        ];
    }
}
