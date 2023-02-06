<?php

namespace App\Http\Resources\TrackDay;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TrackDay\Item as TrackDayItemResource;
use App\Http\Resources\Dialog\Item as DialogItemResource;

class Enquiry extends JsonResource
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
            'message' => $this->message,
            'trackDay' => new TrackDayItemResource($this->trackDay),
            'dialog_id' => ! empty($this->dialog) ? $this->dialog->id : null,
            //'status' =>  ! empty($this->dialog) ? $this->dialog->status : 1,
            'status' =>  0,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
