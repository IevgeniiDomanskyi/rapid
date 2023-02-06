<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\TrackDay\AssignCoach as TrackDayAssignCoachNotification;
use App\Notifications\TrackDay\Booked as TrackDayBookedNotification;
use App\Notifications\TrackDay\BookCreated as TrackDayBookCreatedNotification;
use App\Notifications\Admin\TrackDayBooked as AdminTrackDayBookedNotification;
use App\Notifications\Admin\TrackDayEnquiry as AdminTrackDayEnquiryNotification;

class TrackDaySubscriber
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
        $event->trackDay->coach->notify((new TrackDayAssignCoachNotification($event->trackDay)));
    }

    public function onBooked($event)
    {
        if ($event->customer->gdpr) {
            $auth_token = md5(time().$event->trackDay->id.$event->trackDay->name);
            service('User')->update($event->customer, [
                'auth_token' => $auth_token,
            ]);

            $event->customer->notify(new TrackDayBookedNotification($event->trackDay, $event->customer, $auth_token));
        }

        $admins = service('User')->admins();
        foreach ($admins as $admin) {
            if ($admin->email != 'natalie@adkca.co.uk') {
                $admin->notify(new AdminTrackDayBookedNotification($event->trackDay));
            }
        }
    }

    public function onBookCreated($event)
    {
        if ($event->user->gdpr) {
            $event->user->notify(new TrackDayBookCreatedNotification($event->trackDay, $event->user, $event->request));
        }

        $admins = service('User')->admins();
        foreach ($admins as $admin) {
            if ($admin->email != 'natalie@adkca.co.uk') {
                $admin->notify(new AdminTrackDayBookedNotification($event->trackDay));
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
                $admin->notify(new AdminTrackDayEnquiryNotification($event->enquiry));
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
            'App\Events\TrackDay\AssignCoach',
            'App\Listeners\TrackDaySubscriber@onAssignCoach'
        );

        $events->listen(
            'App\Events\TrackDay\Booked',
            'App\Listeners\TrackDaySubscriber@onBooked'
        );

        $events->listen(
            'App\Events\TrackDay\BookCreated',
            'App\Listeners\TrackDaySubscriber@onBookCreated'
        );

        $events->listen(
            'App\Events\TrackDay\EnquiryCreated',
            'App\Listeners\TrackDaySubscriber@onEnquiryCreated'
        );
    }
}
