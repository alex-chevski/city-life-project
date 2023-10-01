<?php

declare(strict_types=1);

namespace App\Services\Sms;

use App\Mail\NotificationPhoneMail;
use App\Mail\VerifyPhoneMail;
use App\Models\User\User;
use Illuminate\Contracts\Mail\Mailer as MailerInterface;

// for default
class EmailSms implements SmsSender
{
    private MailerInterface $mailer;
    private User $user;

    public function __construct(MailerInterface $mailer, User $user)
    {
        $this->mailer = $mailer;
        $this->user = $user;
    }

    public function send($number, $text): void
    {
        $user = $this->user->getByPhone($number);

        if (is_numeric($text)) {
            $this->mailer->to($user->email)->send(new VerifyPhoneMail($number, $text));
        } else {
            $this->mailer->to($user->email)->send(new NotificationPhoneMail($number, $text));
        }
    }
}
