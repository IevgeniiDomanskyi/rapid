<?php

namespace App\Http\Resources\Dialog;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Book\Item as BookItemResource;
use App\Http\Resources\Book\Enquiry as BookEnquiryResource;
use App\Http\Resources\User\Item as UserItemResource;
use App\Http\Resources\Message\Item as MessageItemResource;

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
        if (empty($this->id)) {
            return [];
        }

        return [
            'id' => $this->id,
            'book' => new BookItemResource($this->book),
            'enquiry' => new BookEnquiryResource($this->enquiry),
            'customer' => new UserItemResource($this->customer),
            'coach' => new UserItemResource($this->coach),
            'messages' => MessageItemResource::collection($this->messages->sortByDesc('created_at')),
            'status' => $this->status,
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
