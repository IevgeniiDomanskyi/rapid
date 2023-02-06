<?php

namespace App\Http\Resources\Address;

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
            'line1' => $this->line1,
            'line2' => $this->line2,
            'town' => $this->town,
            'city' => $this->city,
            'county' => $this->county,
            'country' => $this->country,
            'postcode' => $this->postcode,
        ];
    }
}
