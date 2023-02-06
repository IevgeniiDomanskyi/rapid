<?php

namespace App\Notifications\Coach;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Book;
use App\User;

class Assign extends Notification implements ShouldQueue
{
    use Queueable;

    public $book;
    public $coach;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Book $book, User $coach)
    {
        $this->book = $book;
        $this->coach = $coach;
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
        return (new MailMessage)->subject(__('You have been assigned to a new booking'))->markdown('emails.coach.assign', ['book' => $this->book, 'coach' => $this->coach, 'link' => $this->link]);
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
            'book' => $this->book,
            'coach' => $this->coach,
            'link' => $this->link,
        ];
    }
}
