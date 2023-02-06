<?php

namespace App\Http\Resources\Doc;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'type' => $this->type,
            'file' => $this->file != '' ? Storage::url($this->file) : '',
            'course' => ! empty($this->docable->course) ? ($this->docable->course->title.($this->docable->level->level > 0 ? ' '.$this->docable->level->level : '')) : '',
            'is_signed' => $this->is_signed,
            'date' => optional($this->date)->format('d/m/Y'),
            'same' => now()->startOfDay()->diffInDays($this->date) <= 2,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i:s'),
        ];
    }
}
