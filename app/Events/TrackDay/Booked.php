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

class Booked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trackDay;
    public $customer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TrackDay $trackDay, User $customer)
    {
        $this->trackDay = $trackDay;
        $this->customer = $customer;
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
