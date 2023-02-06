<?php

namespace App\Notifications\Event;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Event;
use App\User;

class Booked extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $customer;
    public $declaration;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Event $event, User $customer, $token)
    {
        $this->event = $event;
        $this->customer = $customer;
        $this->declaration = env('APP_URL').'/panel/magic/'.$token.'/documents';
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
        return (new MailMessage)->subject(__('Thank you for your order'))->markdown('emails.event.booked', ['event' => $this->event, 'customer' => $this->customer, 'declaration' => $this->declaration]);
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
            'customer' => $this->customer,
            'declaration' => $this->declaration,
        ];
    }
}
