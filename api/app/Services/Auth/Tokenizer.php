<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User\Token;
use DateInterval;
use DateTimeImmutable;
use Illuminate\Support\Str;

/**
 * Class Tokenizer.
 * @author yourname
 */
class Tokenizer
{
    public function __construct()
    {
    }

    public function generateNew(DateTimeImmutable $date): Token
    {
        $interval = new DateInterval('PT5M');

        return new Token(
            Str::uuid()->toString(),
            $date->add($interval)
        );
    }

    public function generateOld(string $token, DateTimeImmutable $date): Token
    {
        return new Token(
            $token,
            $date,
        );
    }
}
