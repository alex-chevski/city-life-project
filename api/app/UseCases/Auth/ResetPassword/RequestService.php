<?php

declare(strict_types=1);

namespace App\UseCases\Auth\ResetPassword;

use App\Mail\ResetPasswordMail;
use App\Models\User\User;
use App\Services\Auth\Tokenizer;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Contracts\Mail\Mailer as MailerInterface;

/**
 * Class RegisterService.
 * @author frostep
 */
class RequestService
{
    private $mailer;
    private User $user;
    private Tokenizer $tokenizer;

    /**
     * @param Mailer $mailer
     */
    public function __construct(
        MailerInterface $mailer,
        User $user,
        Tokenizer $tokenizer,
    ) {
        $this->mailer = $mailer;
        $this->user = $user;
        $this->tokenizer = $tokenizer;
    }

    public function requestResetLink(string $email): void
    {
        $user = $this->user->getByEmail($email);

        $date = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));

        $user->requestPasswordReset(
            $this->tokenizer,
            $date
        );

        $this->mailer->to($user->email)->send(new ResetPasswordMail($user));

        // $this->dispatcher->dispatch(new Registered($user));
    }
}
