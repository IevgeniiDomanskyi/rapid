<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangePassword extends Notification implements ShouldQueue
{
    use Queueable;

    public $password;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $password)
    {
        $this->password = $password;
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
        return (new MailMessage)->subject(__('Your Password was changed'))->markdown('emails.auth.change_password', ['password' => $this->password, 'link' => $this->link]);
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
            'password' => $this->password,
            'link' => $this->link,
        ];
    }
}
