<?php

declare(strict_types=1);

namespace App\UseCases\Auth\ResetPassword;

use App\Models\User\User;
use App\Services\Auth\Tokenizer\TokenizerMail;
use Carbon\Carbon;

class ResetService
{
    public function __construct(
        private User $user,
        private TokenizerMail $tokenizer,
        private Carbon $date
    ) {
        $this->user = $user;
        $this->tokenizer = $tokenizer;
        $this->date = $date;
    }

    public function reset(string $token, string $password): void
    {
        $user = $this->user->findByVerifyToken($token, 'mail');

        $user->resetPassword(
            $token,
            $this->date->copy(),
            $password,
            $this->tokenizer->default($user->verify_token),
        );

        // $this->dispatcher->dispatch(new Registered($user));
    }
}
