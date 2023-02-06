<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Book\Item as BookItemResource;

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
            'user' => $this->user,
            'book' => new BookItemResource($this->book),
            'amount' => $this->amount,
            'instalment' => $this->instalment,
            'status' => $this->status,
            'due_date' => $this->due_date->format('d/m/Y H:i'),
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
