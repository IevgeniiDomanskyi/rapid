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
use App\User;

class AssignCoach
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $book;
    public $coach;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Book $book, User $coach, User $user)
    {
        $this->book = $book;
        $this->coach = $coach;
        $this->user = $user;
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
