<?php

namespace App\Http\Resources\Date;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
{
    public $preserveKeys = true;
    
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
            'date' => $this->date->format('Y-m-d'),
            'date_human' => $this->date->format('d/m/Y'),
            'road' => optional($this->pivot)->road,
            $this->mergeWhen( ! empty($this->books), [
                'slots' => $this->getSlotList($this->books),
                'am' => $this->getSlot($this->books, 1),
                'pm' => $this->getSlot($this->books, 2),
            ]),
        ];
    }

    public function getSlot($books, $slot = 1)
    {
        foreach ($books as $book) {
            if ($book->pivot->slot == $slot || $book->pivot->slot == 3) {
                return true;
            }
        }

        return false;
    }

    public function getSlotList($books)
    {
        $temp = [];
        foreach ($books as $book) {            
            $temp[$book->pivot->road] = $book->pivot->slot;
        }

        return $temp;
    }
}
