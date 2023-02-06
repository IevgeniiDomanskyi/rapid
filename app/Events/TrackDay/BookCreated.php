<?php

namespace App\Events\TrackDay;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\TrackDay;
use App\User;

class BookCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trackDay;
    public $user;
    public $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TrackDay $trackDay, User $user, $request)
    {
        $this->trackDay = $trackDay;
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
