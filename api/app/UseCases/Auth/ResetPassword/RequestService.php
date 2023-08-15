<?php

declare(strict_types=1);

namespace App\UseCases\Auth\ResetPassword;

use App\Mail\ResetPasswordMail;
use App\Models\User\User;
use App\Services\Auth\Tokenizer;
use Carbon\Carbon;
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
    private Carbon $date;

    /**
     * @param Mailer $mailer
     */
    public function __construct(
        MailerInterface $mailer,
        User $user,
        Tokenizer $tokenizer,
        Carbon $date,
    ) {
        $this->mailer = $mailer;
        $this->user = $user;
        $this->tokenizer = $tokenizer;
        $this->date = $date;
    }

    public function requestResetLink(string $email): void
    {
        $user = $this->user->getByEmail($email);

        $user->requestPasswordReset(
            $this->tokenizer,
            $this->date->copy(),
        );

        $this->mailer->to($user->email)->send(new ResetPasswordMail($user));

        // $this->dispatcher->dispatch(new Registered($user));
    }
}
