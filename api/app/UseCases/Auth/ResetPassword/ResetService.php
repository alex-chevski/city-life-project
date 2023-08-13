<?php

declare(strict_types=1);

namespace App\UseCases\Auth\ResetPassword;

use App\Models\User\User;
use App\Services\Auth\Tokenizer;
use Carbon\Carbon;

class ResetService
{
    public function __construct(
        private User $user,
        private Tokenizer $tokenizer,
        private Carbon $date
    ) {
        $this->user = $user;
        $this->tokenizer = $tokenizer;
        $this->date = $date;
    }

    public function reset(string $token, string $password): void
    {
        $user = $this->user->findByPasswordResetToken($token);

        $user->resetPassword(
            $token,
            $this->date->copy()->setTimezone('Europe/Moscow'),
            $password,
            $this->tokenizer->generateOld($user->verify_token, $this->date->copy()->setTimeFromTimeString($user->expires)),
        );

        // $this->dispatcher->dispatch(new Registered($user));
    }
}
