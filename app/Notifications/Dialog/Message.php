<?php

namespace App\Notifications\Dialog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Dialog;

class Message extends Notification implements ShouldQueue
{
    use Queueable;

    public $book;
    public $dialog;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Dialog $dialog, string $token)
    {
        $this->book = ! empty($dialog->book) ? $dialog->book : $dialog->enquiry;
        $this->dialog = $dialog;
        $this->link = env('APP_URL').'/panel/magic/'.$token.'/dialog/'.$dialog->id;
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
        return (new MailMessage)->subject(__('You have a new message available on My Rapid Journey!'))->markdown('emails.dialog.message', ['book' => $this->book, 'dialog' => $this->dialog, 'link' => $this->link]);
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
            'dialog' => $this->dialog,
            'link' => $this->link,
        ];
    }
}
