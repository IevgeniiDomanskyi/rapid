<?php

namespace App\Notifications\Book;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Payment;

class RequestPayment extends Notification implements ShouldQueue
{
    use Queueable;

    public $payment;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Payment $payment, string $token)
    {
        $this->payment = $payment;
        $this->link = env('APP_URL').'/panel/magic/'.$token.'/payment/'.$payment->id;
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
        return (new MailMessage)->subject(__('Please provide your card details to complete your booking'))->markdown('emails.book.request-payment', ['payment' => $this->payment, 'link' => $this->link]);
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
            'payment' => $this->payment,
            'link' => $this->link,
        ];
    }
}
