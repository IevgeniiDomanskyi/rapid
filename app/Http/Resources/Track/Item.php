<?php

namespace App\Http\Resources\Track;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Region\Item as RegionItemResource;

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
            'name' => $this->name,
            'region' => new RegionItemResource($this->region),
            'email' => $this->email,
        ];
    }
}
