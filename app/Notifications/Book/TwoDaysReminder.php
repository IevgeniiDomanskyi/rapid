<?php

namespace App\Notifications\Book;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Book;

class TwoDaysReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $book;
    public $declaration;
    public $type;
    public $date;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Book $book, $type, $date, $token)
    {
        $this->book = $book;
        // $this->declaration = env('APP_URL').'/pdf/RAPID-TRAINING-LIMITED-T&CDeclaration.pdf';
        $this->declaration = env('APP_URL').'/panel/magic/'.$token.'/documents';
        $this->type = $type;
        $this->date = $date;
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
        return (new MailMessage)->subject(__('We are looking forward to seeing you in 2 days!'))->markdown('emails.book.two-days-reminder', ['book' => $this->book, 'declaration' => $this->declaration, 'type' => $this->type, 'date' => $this->date]);
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
            'declaration' => $this->declaration,
            'type' => $this->type,
            'date' => $this->date,
        ];
    }
}
