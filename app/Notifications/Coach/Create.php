<?php

namespace App\Notifications\Coach;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class Create extends Notification implements ShouldQueue
{
    use Queueable;

    public $coach;
    public $password;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $coach, string $password)
    {
        $this->coach = $coach;
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
        return (new MailMessage)->subject(__('Welcome to Rapid Training '))->markdown('emails.coach.create', ['coach' => $this->coach, 'password' => $this->password, 'link' => $this->link]);
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
            'link' => $this->link,
        ];
    }
}
