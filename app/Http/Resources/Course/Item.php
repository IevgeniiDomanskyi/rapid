<?php

namespace App\Http\Resources\Course;

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
            'title' => $this->title,
            'description' => $this->description,
            $this->mergeWhen( ! empty($this->levels), [
                'levels' => $this->levels,
            ]),
        ];
    }
}
