<?php

declare(strict_types=1);

namespace App\Services\Auth\Tokenizer\Interface;

use App\Models\User\Token;
use Carbon\Carbon;

interface Tokenizer
{
    public function generate(Carbon $now): Token;

    public function default(string $token): Token;
}
