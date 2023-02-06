<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Auth\Register as AuthRegisterNotification;
use App\Notifications\Auth\Recovery as AuthRecoveryNotification;
use App\Notifications\Auth\ChangeEmail as AuthChangeEmailNotification;
use App\Notifications\Auth\ChangePassword as AuthChangePasswordNotification;
use App\Hash;

class AuthSubscriber
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

    public function onRegister($event)
    {
        $hash = service('Hash')->create($event->user, Hash::TYPE_VERIFY);
        $link = str_replace('{hash}', $hash, $event->link);

        $event->user->notify((new AuthRegisterNotification($event->user, $link)));
    }

    public function onRecovery($event)
    {
        $hash = service('Hash')->create($event->user, Hash::TYPE_RECOVERY);

        $event->user->notify((new AuthRecoveryNotification($event->password)));
    }

    public function onChangeEmail($event)
    {
        $hash = service('Hash')->create($event->user, Hash::TYPE_VERIFY);
        $link = str_replace('{hash}', $hash, $event->link);

        $event->user->notify((new AuthChangeEmailNotification($event->email, $link)));
    }

    public function onChangePassword($event)
    {
        $event->user->notify((new AuthChangePasswordNotification($event->password)));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Auth\Register',
            'App\Listeners\AuthSubscriber@onRegister'
        );

        $events->listen(
            'App\Events\Auth\Recovery',
            'App\Listeners\AuthSubscriber@onRecovery'
        );

        $events->listen(
            'App\Events\Auth\ChangeEmail',
            'App\Listeners\AuthSubscriber@onChangeEmail'
        );

        $events->listen(
            'App\Events\Auth\ChangePassword',
            'App\Listeners\AuthSubscriber@onChangePassword'
        );
    }
}
