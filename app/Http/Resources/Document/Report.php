<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Report extends JsonResource
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
            'type' => $this->typeText($this->type),
            'file' => $this->file != '' ? Storage::url($this->file) : '',
            'course' => ! empty($this->book->course) ? ($this->book->course->title.($this->book->level->level > 0 ? ' '.$this->book->level->level : '')) : '',
            'date' => optional($this->date)->format('d/m/Y'),
            'user' => $this->user,
        ];
    }

    public function typeText($type)
    {
        switch ($type) {
            case 'certificate': return 'Certificate';
            case 'congratulation': return 'Completion letter';
            case 'report': return 'Rider Report';
            default: return 'Unknown';
        }
    }
}
