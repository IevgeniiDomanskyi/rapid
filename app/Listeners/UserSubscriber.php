<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\User\CustomerSave as UserCustomerSaveNotification;

class UserSubscriber
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

    public function onCustomerSave($event)
    {
        if ($event->customer->gdpr) {
            $event->customer->notify((new UserCustomerSaveNotification($event->customer, $event->password)));
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\User\CustomerSave',
            'App\Listeners\UserSubscriber@onCustomerSave'
        );
    }
}
