<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class StudentEmailCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return (new MailMessage)
                    ->subject('JU Student Email Created')
                    ->greeting('Hello!')
                    ->line('Your Student Email Has been Created. Below is Your Credential.')
                    ->line(new HtmlString("<strong>Email : </strong>$notifiable->username@juniv.edu"))
                    ->line(new HtmlString("<strong>Password :</strong> $notifiable->password"))
                    ->action('Webmail Login', 'https://mail.google.com/a/juniv.edu/')
                    ->line('Email Login Instruction is Attachted With Email.')
                    ->attach(public_path('Email-login-instruction.pdf'));
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
            //
        ];
    }
}
