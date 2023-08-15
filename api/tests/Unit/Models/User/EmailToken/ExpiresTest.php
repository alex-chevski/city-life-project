<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User\EmailToken;

use App\Models\User\Token;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @internal
 */
final class ExpiresTest extends TestCase
{
    public function testNot(): void
    {
        $token = new Token(
            $value = Str::uuid()->toString(),
            $expires = Carbon::now('Europe/Moscow'),
        );

        self::assertFalse($token->isExpiredTo($expires->copy()->modify('-1 secs')));
        self::assertTrue($token->isExpiredTo($expires->copy()));
        self::assertTrue($token->isExpiredTo($expires->copy()->modify('+1 secs')));
    }
}
