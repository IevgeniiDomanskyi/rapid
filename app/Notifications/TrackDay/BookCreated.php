<?php

namespace App\Notifications\TrackDay;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\TrackDay;
use App\User;

class BookCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $trackDay;
    public $user;
    public $request;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrackDay $trackDay, User $user, $request)
    {
        $this->trackDay = $trackDay;
        $this->user = $user;
        $this->request = $request;
        $this->link = env('APP_URL').'/panel/payment';
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
        $subject = empty($this->request) ? 'Thank you for your order' : 'Please provide your card details to complete your booking';
        return (new MailMessage)->subject(__($subject))->markdown('emails.trackDay.book-created', ['trackDay' => $this->trackDay, 'user' => $this->user, 'request' => $this->request, 'link' => $this->link]);
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
            'user' => $this->user,
            'request' => $this->request,
            'link' => $this->link,
        ];
    }
}
