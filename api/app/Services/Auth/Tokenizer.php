<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User\Token;
use Carbon\Carbon;
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

    public function generateNew(Carbon $date): Token
    {
        // $interval = new DateInterval('PT5M');

        return new Token(
            Str::uuid()->toString(),
            $date->addMinutes(5),
        );
    }

    public function generateOld(string $token, Carbon $date): Token
    {
        return new Token(
            $token,
            $date,
        );
    }
}
