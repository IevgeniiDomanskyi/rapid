<?php

namespace App\Notifications\TrackDay;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\TrackDay;
use App\User;

class Booked extends Notification implements ShouldQueue
{
    use Queueable;

    public $trackDay;
    public $customer;
    public $declaration;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrackDay $trackDay, User $customer, $token)
    {
        $this->trackDay = $trackDay;
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
        return (new MailMessage)->subject(__('Thank you for your order'))->markdown('emails.trackDay.booked', ['trackDay' => $this->trackDay, 'customer' => $this->customer, 'declaration' => $this->declaration]);
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
            'customer' => $this->customer,
            'declaration' => $this->declaration,
        ];
    }
}
