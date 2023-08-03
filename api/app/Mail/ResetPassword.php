<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Письмо для сброса пароля')
            ->action('Cбросить пароль', url('password/reset', $this->token));
    }
}
