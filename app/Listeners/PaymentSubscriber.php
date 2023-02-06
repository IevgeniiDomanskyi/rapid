<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Book\Declaration as BookDeclarationNotification;

class PaymentSubscriber
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

    public function onCompleted($event)
    {
        service('Doc')->generate($event->book);
        if ($event->book->user->gdpr) {
            $event->book->user->notify(new BookDeclarationNotification());
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
            'App\Events\Payment\Completed',
            'App\Listeners\PaymentSubscriber@onCompleted'
        );
    }
}
