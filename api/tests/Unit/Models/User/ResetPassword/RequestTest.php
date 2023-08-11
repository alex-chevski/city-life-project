<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User\ResetPassword;

use App\Models\User\User;
use App\Services\Auth\Tokenizer;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @internal
 */
final class RequestTest extends TestCase
{
    use DatabaseTransactions;

    private Tokenizer $tokenizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenizer = $this->tokenizer();
    }

    public function testSuccess(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));

        $user->requestPasswordReset($this->tokenizer, $now);

        self::assertNotNull($token = $user->getPasswordResetToken());

        self::assertEquals($token, $this->tokenizer->generateOld($token->getValue(), $token->getExpires()));
    }

    public function testNotActive(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_WAIT]);
        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));

        $this->expectExceptionMessage('User is not active.');
        $user->requestPasswordReset($this->tokenizer, $now);
    }

    public function testExpired(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $now = new DateTimeImmutable();
        $user->requestPasswordReset($this->tokenizer, $now->modify('+ 1 hour'));

        $newDate = $now->modify('2 hours');

        $user->checkRepeatRequest($token = $user->getPasswordResetToken(), $token->getExpires(), $newDate);

        self::assertEquals($user->getPasswordResetToken(), $token);
    }

    public function testAlready(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE, 'verify_token' => null, 'expires' => null]);
        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow'));

        $user->requestPasswordReset($this->tokenizer, $now);

        $this->expectExceptionMessage('Resetting is already requested. ');
        $user->checkRepeatRequest($user->getPasswordResetToken(), $now);
    }

    private function tokenizer(): Tokenizer
    {
        return new Tokenizer();
    }
}
