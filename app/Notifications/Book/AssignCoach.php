<?php

namespace App\Notifications\Book;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;

class AssignCoach extends Notification implements ShouldQueue
{
    use Queueable;

    public $coach;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $coach, User $user)
    {
        $this->coach = $coach;
        $this->user = $user;
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
        $mail = (new MailMessage)->subject(__('Meet your Rapid Training Coach'))->markdown('emails.book.assign-coach', ['coach' => $this->coach, 'user' => $this->user]); 
        return $mail;
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
            'user' => $this->user,
        ];
    }
}
