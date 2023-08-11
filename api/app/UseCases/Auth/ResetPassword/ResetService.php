<?php

declare(strict_types=1);

namespace App\UseCases\Auth\ResetPassword;

use App\Models\User\User;
use App\Services\Auth\Tokenizer;
use DateTimeImmutable;
use DateTimeZone;

class ResetService
{
    private User $user;

    public function __construct(
        User $user,
        Tokenizer $tokenizer
    ) {
        $this->user = $user;
        $this->tokenizer = $tokenizer;
    }

    public function reset(string $token, string $password): void
    {
        $user = $this->user->findByPasswordResetToken($token);

        // write in container for dependency injection later
        $date = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));

        $user->resetPassword(
            $token,
            $date,
            $password,
            $this->tokenizer->generateOld($user->verify_token, new DateTimeImmutable($user->expires)),
        );

        // $this->dispatcher->dispatch(new Registered($user));
    }
}
