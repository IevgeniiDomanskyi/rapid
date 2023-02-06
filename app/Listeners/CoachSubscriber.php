<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Coach\Create as CoachCreateNotification;
use App\Notifications\Coach\Password as CoachPasswordNotification;

class CoachSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function onCreate($event)
    {
        $event->coach->notify((new CoachCreateNotification($event->coach, $event->password)));
    }

    public function onPassword($event)
    {
        $event->coach->notify((new CoachPasswordNotification($event->coach, $event->password)));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Coach\Create',
            'App\Listeners\CoachSubscriber@onCreate'
        );

        $events->listen(
            'App\Events\Coach\Password',
            'App\Listeners\CoachSubscriber@onPassword'
        );
    }
}
