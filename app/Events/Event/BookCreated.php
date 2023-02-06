<?php

namespace App\Events\Event;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Event;
use App\User;

class BookCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;
    public $user;
    public $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Event $event, User $user, $request)
    {
        $this->event = $event;
        $this->user = $user;
        $this->request = $request;
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
