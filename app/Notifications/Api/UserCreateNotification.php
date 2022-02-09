<?php

namespace App\Notifications\Api;

use App\Classes\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreateNotification extends Notification
{
    use Queueable;

    public $code;

    /**
     * Create a new notification instance.
     * @param string $status
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)->greeting($notifiable->full_name);

        $mailMessage = $mailMessage->subject(Email::makeSubject('Verification Code'));
        $mailMessage = $mailMessage->line('Your has been register successfully on ' . constants('global.site.name'));
        $mailMessage = $mailMessage->line('Verification Code : ' . $this->code);
        $mailMessage = $mailMessage->line('Thank you for using our application!');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
