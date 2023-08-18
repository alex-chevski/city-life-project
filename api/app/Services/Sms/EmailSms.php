<?php

declare(strict_types=1);

namespace App\Services\Sms;

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

        $this->mailer->to($user->email)->send(new VerifyPhoneMail($number, $text));
    }
}
