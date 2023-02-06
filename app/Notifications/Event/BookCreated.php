<?php

namespace App\Notifications\Event;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Event;
use App\User;

class BookCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $user;
    public $request;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Event $event, User $user, $request)
    {
        $this->event = $event;
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
        return (new MailMessage)->subject(__($subject))->markdown('emails.event.book-created', ['event' => $this->event, 'user' => $this->user, 'request' => $this->request, 'link' => $this->link]);
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
            'event' => $this->event,
            'user' => $this->user,
            'request' => $this->request,
            'link' => $this->link,
        ];
    }
}
