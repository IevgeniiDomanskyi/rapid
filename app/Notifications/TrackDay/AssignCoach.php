<?php

namespace App\Notifications\TrackDay;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\TrackDay;

class AssignCoach extends Notification implements ShouldQueue
{
    use Queueable;

    public $trackDay;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrackDay $trackDay)
    {
        $this->trackDay = $trackDay;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject(__('You were assigned to an track day'))->markdown('emails.trackDay.assign-coach', ['trackDay' => $this->trackDay]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'trackDay' => $this->trackDay,
        ];
    }
}
