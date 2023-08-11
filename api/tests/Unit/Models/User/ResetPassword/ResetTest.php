<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User\ResetPassword;

use App\Models\User\User;
use App\Services\Auth\Tokenizer;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @internal
 */
final class ResetTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenizer = $this->tokenizer();
    }

    public function testSuccess(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));

        $tokenizer = $this->tokenizer();

        $user->requestPasswordReset($tokenizer, $now);

        self::assertNotNull($token = $user->getPasswordResetToken());

        $user->resetPassword(
            $token->getValue(),
            $now,
            $hash = 'hash',
            $tokenizer->generateOld($token->getValue(), $token->getExpires()),
        );

        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testInvalidToken(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));

        $user->requestPasswordReset($this->tokenizer, $now);

        $this->expectExceptionMessage('Token is invalid.');
        $user->resetPassword(Str::uuid()->toString(), $now, 'hash', $this->tokenizer->generateOld(Str::uuid()->toString(), $now->modify('+1 hour')));
    }

    public function testExpiredToken(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));
        $user->requestPasswordReset($this->tokenizer, $now->modify('+1 hour'));

        $this->expectExceptionMessage('Token is expired.');
        $user->resetPassword($user->getPasswordResetToken()->getValue(), $now->modify('+1 day'), 'hash', $user->getPasswordResetToken());
    }

    public function testNotRequested(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));

        $this->expectExceptionMessage('Token is invalid.');
        $user->resetPassword(Str::uuid()->toString(), $now, 'hash', $this->tokenizer->generateOld(Str::uuid()->toString(), $now));
    }

    private function tokenizer(): Tokenizer
    {
        return new Tokenizer();
    }
}
