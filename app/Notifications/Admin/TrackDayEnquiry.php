<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\TrackDayEnquiry as TrackDayEnquiryModel;

class TrackDayEnquiry extends Notification implements ShouldQueue
{
    use Queueable;

    public $enquiry;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TrackDayEnquiryModel $enquiry)
    {
        $this->enquiry = $enquiry;
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
        return (new MailMessage)->subject(__('ALERT: A new track day enquiry has arrived'))->markdown('emails.admin.track-day-enquiry', ['enquiry' => $this->enquiry]);
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
            'enquiry' => $this->enquiry,
        ];
    }
}
