<?php

namespace App\Http\Resources\Document;

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
            'course' => ! empty($this->book->course) ? ($this->book->course->title.($this->book->level->level > 0 ? ' '.$this->book->level->level : '')) : '',
            'is_signed' => $this->is_signed,
            'date' => optional($this->date)->format('d/m/Y'),
            'same' => now()->startOfDay()->diffInDays($this->date) <= 2,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i:s'),
        ];
    }
}
