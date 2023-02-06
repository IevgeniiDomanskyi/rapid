<?php

namespace App\Events\Coach;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\User;

class Password
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $coach;
    public $password;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $coach, string $password)
    {
        $this->coach = $coach;
        $this->password = $password;
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
