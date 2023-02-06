<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Enquiry\Created as EnquiryCreatedNotification;
use App\Notifications\Admin\Enquiry as AdminEnquiryNotification;

class EnquirySubscriber
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

    public function onCreated($event)
    {
        $dialog = service('Dialog')->createEnquiry($event->enquiry, $event->enquiry->user);
        $message = service('Dialog')->message($dialog, ['message' => $event->enquiry->message], true, $event->enquiry->user);
        if ($event->enquiry->user->gdpr) {
            $event->enquiry->user->notify((new EnquiryCreatedNotification($event->enquiry)));
        }

        $admins = service('User')->admins();
        foreach ($admins as $admin) {
            if ($admin->email != 'natalie@adkca.co.uk') {
                $admin->notify(new AdminEnquiryNotification($event->enquiry));
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
            'App\Events\Enquiry\Created',
            'App\Listeners\EnquirySubscriber@onCreated'
        );
    }
}
