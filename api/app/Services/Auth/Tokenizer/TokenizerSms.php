<?php

declare(strict_types=1);

namespace App\Services\Auth\Tokenizer;

use App\Models\User\Token;
use App\Models\User\User;
use App\Services\Auth\Tokenizer\Interface\Tokenizer;
use Carbon\Carbon;

/**
 * Class.
 * @author yourname
 */
class TokenizerSms implements Tokenizer
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function generate(Carbon $now): Token
    {
        return new Token(
            (string)random_int(10000, 99999),
            $now->addSeconds(300),
        );
    }

    public function default(string $token): Token
    {
        $user = $this->user->findByVerifyToken($token, 'sms');

        return new Token(
            $user->phone_verify_token,
            $user->phone_verify_token_expire,
        );
    }
}
