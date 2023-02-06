<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\TrackDay;

class TrackDayBooked extends Notification implements ShouldQueue
{
    use Queueable;

    public $trackDay;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrackDay $trackDay)
    {
        $this->trackDay = $trackDay;
        $this->link = env('APP_URL').'/panel';
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
        return (new MailMessage)->subject(__('ALERT: A new track day order has been placed'))->markdown('emails.admin.track-day-booked', ['trackDay' => $this->trackDay, 'link' => $this->link]);
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
            'link' => $this->link,
        ];
    }
}
