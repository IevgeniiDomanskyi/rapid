<?php

namespace App\Events\Book;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Book;

class Pay
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $book;
    public $amount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Book $book, $amount)
    {
        $this->book = $book;
        $this->amount = $amount;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
