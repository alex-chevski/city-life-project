<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User\ResetPassword;

use App\Models\User\User;
use App\Services\Auth\Tokenizer;
use Carbon\Carbon;
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
        $this->now = Carbon::now('Europe/Moscow');
    }

    public function testSuccess(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE, 'verify_token' => null, 'expires' => null]);

        $user->requestPasswordReset($this->tokenizer, $this->now);

        self::assertNotNull($token = $user->getPasswordResetToken());

        self::assertEquals($token, $this->tokenizer->generate($token->getExpires(), 'default', $token->getValue()));
    }

    public function testNotActive(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_WAIT]);

        $this->expectExceptionMessage('User is not active.');
        $user->requestPasswordReset($this->tokenizer, $this->now);
    }

    public function testExpired(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $user->requestPasswordReset($this->tokenizer, $this->now->modify('+ 1 hour'));

        $newDate = $this->now->modify('2 hours');

        $user->checkRepeatRequest($token = $user->getPasswordResetToken(), $token->getExpires(), $newDate);

        self::assertEquals($user->getPasswordResetToken(), $token);
    }

    public function testAlready(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE, 'verify_token' => null, 'expires' => null]);

        $user->requestPasswordReset($this->tokenizer, $this->now->copy());

        $this->expectExceptionMessage('Resetting is already requested. ');
        $user->checkRepeatRequest($user->getPasswordResetToken(), $this->now->copy());
    }

    private function tokenizer(): Tokenizer
    {
        return new Tokenizer();
    }
}
