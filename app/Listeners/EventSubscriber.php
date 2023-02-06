<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Event\AssignCoach as EventAssignCoachNotification;
use App\Notifications\Event\Booked as EventBookedNotification;
use App\Notifications\Event\BookCreated as EventBookCreatedNotification;
use App\Notifications\Admin\EventBooked as AdminEventBookedNotification;
use App\Notifications\Admin\EventEnquiry as AdminEventEnquiryNotification;

class EventSubscriber
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

    public function onAssignCoach($event)
    {
        $event->event->coach->notify((new EventAssignCoachNotification($event->event)));
    }

    public function onBooked($event)
    {
        if ($event->customer->gdpr) {
            $auth_token = md5(time().$event->event->id.$event->event->name);
            service('User')->update($event->customer, [
                'auth_token' => $auth_token,
            ]);

            $event->customer->notify(new EventBookedNotification($event->event, $event->customer, $auth_token));
        }

        $admins = service('User')->admins();
        foreach ($admins as $admin) {
            if ($admin->email != 'natalie@adkca.co.uk') {
                $admin->notify(new AdminEventBookedNotification($event->event));
            }
        }
    }

    public function onBookCreated($event)
    {
        if ($event->user->gdpr) {
            $event->user->notify(new EventBookCreatedNotification($event->event, $event->user, $event->request));
        }

        $admins = service('User')->admins();
        foreach ($admins as $admin) {
            if ($admin->email != 'natalie@adkca.co.uk') {
                $admin->notify(new AdminEventBookedNotification($event->event));
            }
        }
    }

    public function onEnquiryCreated($event)
    {
        /* $dialog = service('Dialog')->createEnquiry($event->enquiry, $event->enquiry->user);
        $message = service('Dialog')->message($dialog, ['message' => $event->enquiry->message], true, $event->enquiry->user);
        if ($event->enquiry->user->gdpr) {
            $event->enquiry->user->notify((new EnquiryCreatedNotification($event->enquiry)));
        } */

        $admins = service('User')->admins();
        foreach ($admins as $admin) {
            if ($admin->email != 'natalie@adkca.co.uk') {
                $admin->notify(new AdminEventEnquiryNotification($event->enquiry));
            }
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
            'App\Events\Event\AssignCoach',
            'App\Listeners\EventSubscriber@onAssignCoach'
        );

        $events->listen(
            'App\Events\Event\Booked',
            'App\Listeners\EventSubscriber@onBooked'
        );

        $events->listen(
            'App\Events\Event\BookCreated',
            'App\Listeners\EventSubscriber@onBookCreated'
        );

        $events->listen(
            'App\Events\Event\EnquiryCreated',
            'App\Listeners\EventSubscriber@onEnquiryCreated'
        );
    }
}
