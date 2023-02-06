<?php

namespace App\Notifications\Book;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Book;

class TrackPay extends Notification implements ShouldQueue
{
    use Queueable;

    public $declaration;
    public $book;
    public $amount;
    public $dates;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Book $book, $amount, $token)
    {
        // $this->declaration = env('APP_URL').'/pdf/RAPID-TRAINING-LIMITED-T&CDeclaration.pdf';
        $this->declaration = env('APP_URL').'/panel/magic/'.$token.'/documents';
        $this->book = $book;
        $this->amount = $amount;

        $data = [];
        foreach ($book->dates as $date) {
            $data[$date->pivot->road] = $date->date->format('d/m/Y').' ('.$this->getSlot($date->pivot->slot).')';
        }
        ksort($data);
        $this->dates = $data;
    }

    public function getSlot($slot)
    {
        switch ($slot) {
            case 1: return 'AM';
            case 2: return 'PM';
            case 3: return 'Full Day';
            default: return 'Unknonw';
        }
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
        return (new MailMessage)->subject(__('You are all set!'))->markdown('emails.book.track-pay', ['declaration' => $this->declaration, 'book' => $this->book, 'amount' => $this->amount, 'dates' => $this->dates]);
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
            'declaration' => $this->declaration,
            'book' => $this->book,
            'amount' => $this->amount,
            'dates' => $this->dates,
        ];
    }
}
