<?php

namespace App\Http\Resources\Level;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Course\Item as CourseItemResource;

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
            'course' => $this->course,
            'level' => $this->level,
            'price' => $this->price,
            'fee' => $this->fee,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
