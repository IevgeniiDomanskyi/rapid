<?php

namespace App\Events\TrackDay;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\TrackDayEnquiry;

class EnquiryCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $enquiry;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TrackDayEnquiry $enquiry)
    {
        $this->enquiry = $enquiry;
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
