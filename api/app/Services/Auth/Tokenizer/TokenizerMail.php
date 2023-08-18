<?php

declare(strict_types=1);

namespace App\Services\Auth\Tokenizer;

use App\Models\User\Token;
use App\Models\User\User;
use App\Services\Auth\Tokenizer\Interface\Tokenizer;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Class.
 * @author yourname
 */
class TokenizerMail implements Tokenizer
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function generate(Carbon $now): Token
    {
        return new Token(
            Str::uuid()->toString(),
            $now->addSeconds(300),
        );
    }

    public function default(string $token): Token
    {
        $user = $this->user->findByVerifyToken($token, 'mail');

        return new Token(
            $user->verify_token,
            $user->expires,
        );
    }
}
