<?php

namespace App\Notifications\Coach;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class Password extends Notification implements ShouldQueue
{
    use Queueable;

    public $coach;
    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $coach, string $password)
    {
        $this->coach = $coach;
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
        return (new MailMessage)->subject(__('Your password was changed'))->markdown('emails.coach.password', ['coach' => $this->coach, 'password' => $this->password]);
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
            'coach' => $this->coach,
            'password' => $this->password,
        ];
    }
}
