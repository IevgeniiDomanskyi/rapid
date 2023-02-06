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

class AssignCoach
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trackDay;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TrackDay $trackDay)
    {
        $this->trackDay = $trackDay;
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
