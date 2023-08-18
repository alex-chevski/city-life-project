<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User\ResetPassword;

use App\Models\User\User;
use App\Services\Auth\Tokenizer\Interface\Tokenizer;
use App\Services\Auth\Tokenizer\TokenizerMail;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\App;
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
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE, 'verify_token' => null, 'expires' => null]);

        $tokenizer = $this->tokenizer();

        $user->requestPasswordReset($tokenizer, $this->now->copy());

        self::assertNotNull($token = $user->getPasswordResetToken());

        $user->resetPassword(
            $token->getValue(),
            $this->now,
            $hash = 'hash',
            $tokenizer->default($token->getValue()),
        );

        self::assertEquals($hash, $user->getPasswordHash());
    }

    public function testInvalidToken(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE, 'verify_token' => null, 'expires' => null]);

        $user->requestPasswordReset($this->tokenizer, $this->now);

        $this->expectExceptionMessage('Incorrect verify token. ');
        $user->resetPassword(Str::uuid()->toString(), $this->now, 'hash', $this->tokenizer->default(Str::uuid()->toString()));
    }

    public function testExpiredToken(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE, 'verify_token' => null, 'expires' => null]);
        $user->requestPasswordReset($this->tokenizer, $this->now->copy()->modify('+1 hour'));

        $this->expectExceptionMessage('Token is expired.');
        $user->resetPassword($user->getPasswordResetToken()->getValue(), $this->now->copy()->modify('+1 day'), 'hash', $user->getPasswordResetToken());
    }

    public function testNotRequested(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $this->expectExceptionMessage('Incorrect verify token. ');
        $user->resetPassword(Str::uuid()->toString(), $this->now, 'hash', $this->tokenizer->default(Str::uuid()->toString()));
    }

    private function tokenizer(): Tokenizer
    {
        return App::make(TokenizerMail::class);
    }
}
