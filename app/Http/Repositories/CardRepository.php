<?php

namespace App\Http\Repositories;

use App\User;
use App\Card;

class CardRepository
{
    public function get($id)
    {
        return Card::find($id);
    }
    
    public function all(User $user)
    {
        return $user->cards;
    }

    public function create(array $data)
    {
        return Card::create($data);
    }

    public function update(Card $card, array $data)
    {
        $card->fill($data);
        $card->save();
        return $card;
    }

    public function attach(User $user, Card $card)
    {
        $card->user()->associate($user);
        $card->save();
        return $card;
    }

    public function remove(Card $card)
    {
        return $card->delete();
    }
}
