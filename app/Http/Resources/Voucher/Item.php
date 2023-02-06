<?php

namespace App\Http\Resources\Voucher;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Level\Item as LevelItemResource;

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
            'amount' => $this->amount,
            'code' => $this->code,
            'excluded' => LevelItemResource::collection($this->levels),
            'coupon_limit' => $this->coupon_limit,
            'user_limit' => $this->user_limit,
            'expired_at' => ! empty($this->expired_at) ? $this->expired_at->format('d/m/Y') : '',
            'redemptions' => $this->books->count(),
        ];
    }
}
