<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User\Token;

use App\Models\User\Token;
use DateTimeImmutable;
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
            $expires = new DateTimeImmutable(),
        );

        self::assertFalse($token->isExpiredTo($expires->modify('-1 secs')));
        self::assertTrue($token->isExpiredTo($expires));
        self::assertTrue($token->isExpiredTo($expires->modify('+1 secs')));
    }
}
