<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class StudentEmailApplied extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
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
     * @param  mixed  $email
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($email)
    {
        return (new MailMessage)
                    ->subject('JU Student Email Applied')
                    ->greeting('Hello!')
                    ->line('You Have Applied For Student Email, With Below Information')
                    ->line(new HtmlString("<strong>Admission Session : </strong>{$email->admissionSession->name}"))
                    ->line(new HtmlString("<strong>Program :</strong> {$email->program->name}"))
                    ->line(new HtmlString("<strong>Faculty :</strong> {$email->department->faculty->name}"))
                    ->line(new HtmlString("<strong>Department :</strong> {$email->department->name}"))
                    ->line(new HtmlString("<strong>Hall :</strong> {$email->hall->name}"))
                    ->line(new HtmlString("<strong>Registration Number :</strong> $email->registration_number"))
                    ->line(new HtmlString("<strong>Name :</strong> $email->name"))
                    ->line(new HtmlString("<strong>Contact Number :</strong> $email->contact_phone"))
                    ->line(new HtmlString("<strong>Existing Email :</strong> $email->existing_email"))
                    ->line('We Will Email You Credential on Existing Email Very Soon.');
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
