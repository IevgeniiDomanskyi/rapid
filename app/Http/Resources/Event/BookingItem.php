<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\Event\Item as EventItemResource;
use App\Http\Resources\User\Item as UserItemResource;
use App\Http\Resources\Card\Item as CardItemResource;

class BookingItem extends JsonResource
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
            'event' => new EventItemResource($this->event),
            'user' => new UserItemResource($this->user),
            'card' => new CardItemResource($this->card),
            'is_paid' => $this->is_paid,
            'is_bank' => $this->is_bank,
            'booked_at' => ! empty($this->created_at) ? (new Carbon($this->created_at))->format('d/m/Y') : '',
        ];
    }
}
