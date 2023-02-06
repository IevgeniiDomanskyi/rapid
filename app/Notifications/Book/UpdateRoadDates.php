<?php

namespace App\Notifications\Book;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Book;

class UpdateRoadDates extends Notification implements ShouldQueue
{
    use Queueable;

    public $book;
    public $dates;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Book $book, $dates)
    {
        $this->book = $book;

        $data = [];
        foreach ($dates as $date) {
            $data[$date->pivot->road] = $date->date->format('d/m/Y');
        }
        ksort($data);
        $this->dates = $data;
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
        return (new MailMessage)->subject(__('Your Road date has been updated'))->markdown('emails.book.update-road-dates', ['book' => $this->book, 'dates' => $this->dates, 'link' => $this->link]);
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
            'dates' => $this->dates,
            'link' => $this->link,
        ];
    }
}
