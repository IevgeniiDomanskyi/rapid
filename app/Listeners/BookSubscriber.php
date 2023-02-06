<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Book\TrackDateDefined as BookTrackDateDefinedNotification;
use App\Notifications\Book\TrackDateUpdate as BookTrackDateUpdateNotification;
use App\Notifications\Book\TrackPay as BookTrackPayNotification;
use App\Notifications\Book\AgreeRoadDates as BookAgreeRoadDatesNotification;
use App\Notifications\Book\UpdateRoadDates as BookUpdateRoadDatesNotification;
use App\Notifications\Book\RequestPayment as BookRequestPaymentNotification;
use App\Notifications\Book\Created as BookCreatedNotification;
use App\Notifications\Book\AssignCoach as BookAssignCoachNotification;
use App\Notifications\Coach\Assign as CoachAssignNotification;
use App\Notifications\Admin\Book as AdminBookNotification;

class BookSubscriber
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

    public function onTrackDateDefined($event)
    {
        if ($event->book->user->gdpr) {
            $event->book->user->notify((new BookTrackDateDefinedNotification($event->book)));
        }
    }

    public function onTrackDateUpdate($event)
    {
        if ($event->user->gdpr) {
            $event->user->notify((new BookTrackDateUpdateNotification($event->book, $event->trackDate)));
        }
    }

    public function onTrackPay($event)
    {
        if ($event->user->gdpr) {
            $auth_token = md5(time().$event->book->id.$event->book->email);
            service('User')->update($event->book->user, [
                'auth_token' => $auth_token,
            ]);

            $event->user->notify((new BookTrackPayNotification($event->book, $auth_token)));
        }
    }

    public function onAgreeRoadDates($event)
    {
        if ($event->book->user->gdpr) {
            $event->book->user->notify((new BookAgreeRoadDatesNotification($event->book, $event->book->dates)));
        }
    }

    public function onPay($event)
    {
        if ($event->book->user->gdpr) {
            $auth_token = md5(time().$event->book->id.$event->book->email);
            service('User')->update($event->book->user, [
                'auth_token' => $auth_token,
            ]);
            $event->book->user->notify((new BookTrackPayNotification($event->book, $event->amount, $auth_token)));
        }
    }

    public function onUpdateRoadDates($event)
    {
        if ($event->book->user->gdpr) {
            $event->book->user->notify((new BookUpdateRoadDatesNotification($event->book, $event->book->dates)));
        }
    }

    public function onPaymentRequest($event)
    {
        $auth_token = md5(time().$event->book->id.$event->book->email);
        service('User')->update($event->book->user, [
            'auth_token' => $auth_token,
        ]);

        $payment = service('Payment')->create($event->book->user, [
            'book_id' => $event->book->id,
            'amount' => $event->book->level->price,
            'instalment' => $event->book->instalment,
            'status' => false,
            'due_date' => now()->addDay(),
        ]);

        if ($event->book->user->gdpr) {
            $event->book->user->notify((new BookRequestPaymentNotification($payment, $auth_token)));
        }
    }

    public function onCreated($event)
    {
        service('Dialog')->create($event->book, $event->book->user);
        if ($event->book->user->gdpr) {
            $event->book->user->notify(new BookCreatedNotification($event->book, $event->request));
        }

        $admins = service('User')->admins();
        foreach ($admins as $admin) {
            if ($admin->email != 'natalie@adkca.co.uk') {
                $admin->notify(new AdminBookNotification($event->book));
            }
        }
    }

    public function onAssignCoach($event)
    {
        $event->coach->notify(new CoachAssignNotification($event->book, $event->coach));

        if ($event->user->gdpr) {
            $event->user->notify((new BookAssignCoachNotification($event->coach, $event->user)));
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
            'App\Events\Book\TrackDateDefined',
            'App\Listeners\BookSubscriber@onTrackDateDefined'
        );

        $events->listen(
            'App\Events\Book\TrackDateUpdate',
            'App\Listeners\BookSubscriber@onTrackDateUpdate'
        );

        $events->listen(
            'App\Events\Book\TrackPay',
            'App\Listeners\BookSubscriber@onTrackPay'
        );

        $events->listen(
            'App\Events\Book\AgreeRoadDates',
            'App\Listeners\BookSubscriber@onAgreeRoadDates'
        );

        $events->listen(
            'App\Events\Book\Pay',
            'App\Listeners\BookSubscriber@onPay'
        );

        $events->listen(
            'App\Events\Book\UpdateRoadDates',
            'App\Listeners\BookSubscriber@onUpdateRoadDates'
        );

        $events->listen(
            'App\Events\Book\PaymentRequest',
            'App\Listeners\BookSubscriber@onPaymentRequest'
        );

        $events->listen(
            'App\Events\Book\Created',
            'App\Listeners\BookSubscriber@onCreated'
        );

        $events->listen(
            'App\Events\Book\AssignCoach',
            'App\Listeners\BookSubscriber@onAssignCoach'
        );
    }
}
