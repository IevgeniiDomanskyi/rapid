<?php

namespace App\Notifications\Book;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Book;
use App\TrackDate;

class TrackDateUpdate extends Notification implements ShouldQueue
{
    use Queueable;

    public $book;
    public $trackDate;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Book $book, TrackDate $trackDate)
    {
        $this->book = $book;
        $this->trackDate = $trackDate;
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
        return (new MailMessage)->subject(__('Your track date has been updated'))->markdown('emails.book.track-date-update', ['book' => $this->book, 'trackDate' => $this->trackDate, 'link' => $this->link]);
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
            'trackDate' => $this->trackDate,
            'link' => $this->link,
        ];
    }
}
