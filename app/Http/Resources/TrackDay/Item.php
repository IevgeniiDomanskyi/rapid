<?php

namespace App\Http\Resources\TrackDay;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Track\Date as TrackDateResource;

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
            'track_date' => new TrackDateResource($this->trackDate),
            'track_day_id' => $this->trackDayId(),
            'name' => $this->name,
            'coach' => $this->coach,
            'riders1' => $this->riders1,
            'riders2' => $this->riders2,
            'riders3' => $this->riders3,
            'riders' => $this->riders1 + $this->riders2 + $this->riders3,
            'level1' => $this->countLevels($this->customers, 1),
            'level2' => $this->countLevels($this->customers, 2),
            'level3' => $this->countLevels($this->customers, 3),
            'left' => (($this->riders1 + $this->riders2 + $this->riders3) - $this->customers->count()),
            'customers' => $this->customers->count(),
            'price' => $this->price,
            'fee' => $this->fee,
            'booked_at' => ! empty($this->pivot->created_at) ? $this->pivot->created_at->format('d/m/Y') : '',
        ];
    }

    public function countLevels($customers, $level)
    {
        $count = 0;
        foreach ($customers as $customer) {
            if ($customer->pivot->level == $level) {
                $count++;
            }
        }
        return $count;
    }
}
