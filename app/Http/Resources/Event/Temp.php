<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;

class Temp extends JsonResource
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
            'token' => $this->token,
            'step' => $this->step,
            'max' => $this->max,
            'options' => $this->options,
        ];
    }
}
