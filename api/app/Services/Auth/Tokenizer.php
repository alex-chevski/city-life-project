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

    public function generate(Carbon $date,string $typeToken = null, int|string $token = null): Token
    {
        switch ($typeToken){
            case 'mail':
                return new Token(
                    Str::uuid()->toString(),
                    $date->addSeconds(300),
                );
            case 'sms':
                return new Token(
                    (string)random_int(10000, 99999),
                    $date->addSeconds(300),
                );
            default:
                return new Token(
                    $token ?: Str::uuid()->toString(),
                    $date,
                );
        }

    }
}
