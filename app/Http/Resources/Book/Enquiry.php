<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Course\Item as CourseItemResource;
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
            'exp' => $this->exp,
            'message' => $this->message,
            'course' => new CourseItemResource($this->course),
            'dialog_id' => ! empty($this->dialog) ? $this->dialog->id : null,
            'level' => $this->level,
            'status' =>  ! empty($this->dialog) ? $this->dialog->status : 1,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
