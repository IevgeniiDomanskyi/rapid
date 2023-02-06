<?php

namespace App\Http\Repositories;

use App\User;
use App\CardRequest;

class CardRequestRepository
{
    public function get($id)
    {
        return CardRequest::find($id);
    }
    
    public function create(array $data)
    {
        return CardRequest::create($data);
    }

    public function byOrder($order_id)
    {
        return CardRequest::where('order_id', $order_id)->first();
    }

    public function clear(User $user)
    {
        $user->cardRequests()->each(function($item) {
            $item->delete();
        });
    }
}
