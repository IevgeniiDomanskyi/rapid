<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Coach\Item as CoachItemResource;
use App\Http\Resources\Course\Item as CourseItemResource;
use App\Http\Resources\Date\Item as DateItemResource;
use App\Http\Resources\Card\Item as CardItemResource;

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
            'order_id' => $this->orderId(),
            'user' => $this->user,
            'exp' => $this->exp,
            'course' => new CourseItemResource($this->course),
            'level' => $this->level,
            'postcode' => $this->postcode,
            'region' => $this->region,
            'status' => $this->status,
            'statusText' => $this->statusText($this->status, $this->is_paid),
            'statusColor' => $this->statusColor($this->status),
            'coach' => new CoachItemResource($this->coach),
            'card' => new CardItemResource($this->card),
            'created_at' => $this->created_at->format('d/m/Y'),
            'track_date_id' => $this->when(isset($this->trackDate), function () {
                return $this->trackDate->id;
            }),
            'track_date' => $this->when(isset($this->trackDate), function () {
                return $this->trackDate->date->format('d/m/Y');
            }),
            'track_course' => $this->when(isset($this->trackDate), function () {
                return $this->trackDate->course > 0 ? 'Bikemaster '.$this->trackDate->course : '';
            }),
            'road_dates' => DateItemResource::collection($this->dates),
            'road' => $this->road,
            'is_paid' => $this->is_paid,
            'is_bank' => $this->is_bank,
        ];
    }

    public function statusText($status, $paid)
    {
        switch ($status) {
          case 0: return 'New Order';
          case 1: return 'Track Date Confirmed';
          case 2: return 'Track Confirmed';
          case 3: return 'Coach Assigned';
          case 4: return 'Road Dates Agreed';
          case 5: return ($paid ? 'Payment Processed' : 'Waiting for payment');
          case 6: return 'Road Date';
          case 7: return 'Completed';
          case 10: return 'Cancelled';
          default: return 'Undefined';
        }
    }

    public function statusColor($status)
    {
        switch ($status) {
          case 0: return '#009fe3';
          case 1: return '#ee6545';
          case 2: return '#f9b256';
          case 3: return '#c692c2';
          case 4: return '#ee6545';
          case 5: return '#f9b256';
          case 6: return '#c692c2';
          case 7: return '#015322';
          case 10: return '#d53e29';
          default: return '#000000';
        }
    }
}
