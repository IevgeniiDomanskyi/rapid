<?php

namespace App\Http\Repositories;

use App\Dialog;
use App\Message;

class MessageRepository
{
    public function get($id)
    {
        return Message::find($id);
    }
    
    public function all()
    {
        return Message::all();
    }

    public function create(array $data)
    {
        return Message::create($data);
    }

    public function update(Message $message, array $data)
    {
        $message->fill($data);
        $message->save();
        return $message;
    }

    public function attach(Message $message, Dialog $dialog)
    {
        $message->dialog()->associate($dialog);
        $message->save();
        return $message;
    }
}
