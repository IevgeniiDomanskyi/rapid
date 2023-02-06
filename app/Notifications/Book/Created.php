<?php

namespace App\Notifications\Book;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Book;

class Created extends Notification implements ShouldQueue
{
    use Queueable;

    public $book;
    public $request;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Book $book, $request)
    {
        $this->book = $book;
        $this->request = $request;
        $this->link = env('APP_URL').'/panel/payment';
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
        $subject = empty($this->request) ? 'Thank you for booking with Rapid' : 'Please provide your card details to complete your booking';
        return (new MailMessage)->subject(__($subject))->markdown('emails.book.created', ['book' => $this->book, 'request' => $this->request, 'link' => $this->link]);
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
            'request' => $this->request,
            'link' => $this->link,
        ];
    }
}
