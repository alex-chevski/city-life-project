<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User\ResetPassword;

use App\Models\User\User;
use App\Services\Auth\Tokenizer;
use Carbon\Carbon;
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
        $this->now = Carbon::now('Europe/Moscow');
    }

    public function testSuccess(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $tokenizer = $this->tokenizer();

        $user->requestPasswordReset($tokenizer, $this->now->copy());

        self::assertNotNull($token = $user->getPasswordResetToken());

        $user->resetPassword(
            $token->getValue(),
            $this->now,
            $hash = 'hash',
            $tokenizer->generateOld($token->getValue(), $token->getExpires()),
        );

        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testInvalidToken(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $user->requestPasswordReset($this->tokenizer, $this->now);

        $this->expectExceptionMessage('Token is invalid.');
        $user->resetPassword(Str::uuid()->toString(), $this->now, 'hash', $this->tokenizer->generateOld(Str::uuid()->toString(), $this->now->modify('+1 hour')));
    }

    public function testExpiredToken(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
        $user->requestPasswordReset($this->tokenizer, $this->now->modify('+1 hour'));

        $this->expectExceptionMessage('Token is expired.');
        $user->resetPassword($user->getPasswordResetToken()->getValue(), $this->now->modify('+1 day'), 'hash', $user->getPasswordResetToken());
    }

    public function testNotRequested(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $this->expectExceptionMessage('Token is invalid.');
        $user->resetPassword(Str::uuid()->toString(), $this->now, 'hash', $this->tokenizer->generateOld(Str::uuid()->toString(), $this->now));
    }

    private function tokenizer(): Tokenizer
    {
        return new Tokenizer();
    }
}
