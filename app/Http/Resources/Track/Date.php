<?php

namespace App\Http\Resources\Track;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\Track\Item as TrackItemResource;

class Date extends JsonResource
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
            'date' => $this->date->format('d/m/Y'),
            'track' => new TrackItemResource($this->track),
            'riders' => $this->riders,
            'left' => $this->left,
            'level1' => $this->countLevels($this->books, 2),
            'level2' => $this->countLevels($this->books, 3),
            'status' => $this->status,
            'statusColor' => $this->statusColor($this->statusCode($this)),
            'statusText' => $this->statusText($this->statusCode($this)),
        ];
    }

    public function statusCode($obj)
    {
        if (Carbon::now()->diffInMinutes($obj->date, false) <= 0) {
            return 2;
        }

        if ($obj->left > 0) {
            return 0;
        }

        if ($obj->left == 0) {
            return 1;
        }

        return 10;
    }

    public function countLevels($books, $level)
    {
        $count = 0;
        foreach ($books as $book) {
            if ($book->course->id == 1 && $book->level->level == $level) {
                $count++;
            }
        }
        return $count;
    }

    public function statusText($status)
    {
        switch ($status) {
          case 0: return 'Active';
          case 1: return 'Fully Booked';
          case 2: return 'Done';
          default: return 'Undefined';
        }
    }

    public function statusColor($status)
    {
        switch ($status) {
          case 0: return '#40af6b';
          case 1: return '#9d1006';
          case 2: return '#7d7d7d';
          default: return '#000000';
        }
    }
}
