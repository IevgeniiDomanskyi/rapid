<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Book\Item as BookItemResource;
use App\Http\Resources\Event\Item as EventItemResource;

class History extends JsonResource
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
            'amount' => $this->amount,
            'book' => ! empty($this->book) ? new BookItemResource($this->book) : null,
            'event' => ! empty($this->event) ? new EventItemResource($this->event) : null,
            'paid_at' => $this->paid_at->format('d/m/Y'),
            'created_at' => $this->created_at->format('d/m/Y'),
            'status' => $this->paid_at->diffInDays(now()) >= 0,
        ];
    }
}
