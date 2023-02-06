<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Book\Item as BookItemResource;
use App\Http\Resources\User\Item as UserItemResource;

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
            'author' => new UserItemResource($this->author),
            'receiver' => new UserItemResource($this->receiver),
            'message' => $this->message,
            'type' => $this->type,
            'author_read' => optional($this->author_read)->format('H:i d/m'),
            'receiver_read' => optional($this->receiver_read)->format('H:i d/m'),
            'created_at' => $this->created_at->format('H:i d/m'),
        ];
    }
}
