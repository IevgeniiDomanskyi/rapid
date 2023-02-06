<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class CustomerSave extends Notification implements ShouldQueue
{
    use Queueable;

    public $customer;
    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $customer, string $password)
    {
        $this->customer = $customer;
        $this->password = $password;
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
        $link = env('APP_URL').'/panel';
        return (new MailMessage)->subject(__('You were added to RapidTraining'))->markdown('emails.user.customer-save', ['customer' => $this->customer, 'password' => $this->password, 'link' => $link]);
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
            'customer' => $this->customer,
            'password' => $this->password,
        ];
    }
}
