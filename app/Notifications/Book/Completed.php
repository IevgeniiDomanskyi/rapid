<?php

namespace App\Notifications\Book;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Book;

class Completed extends Notification implements ShouldQueue
{
    use Queueable;

    public $book;
    public $documents;
    public $attaches;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Book $book, $token, $attaches)
    {
        $this->book = $book;
        $this->documents = env('APP_URL').'/panel/magic/'.$token.'/documents';
        $this->attaches = $attaches;
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
        $mail = (new MailMessage)->subject(__('Congratulations on completing your Rapid experience'))->markdown('emails.book.completed', ['book' => $this->book, 'documents' => $this->documents]); 
        foreach ($this->attaches as $file) {
            $mail->attach($file);
        }
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
            'book' => $this->book,
            'documents' => $this->documents,
        ];
    }
}
