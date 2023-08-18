<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User\PhoneVerify;

use App\Models\User\User;
use App\Services\Auth\Tokenizer\Interface\Tokenizer;
use App\Services\Auth\Tokenizer\TokenizerSms;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

/**
 * @internal
 */
final class ResponseTest extends TestCase
{
    use DatabaseTransactions;

    private $now;
    private $tokenizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenizer = $this->tokenizer();
        $this->now = Carbon::now();
    }

    public function testVerify(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '79000000000',
            'phone_verified' => false,
            'phone_verify_token' => $token = 'token',
            'phone_verify_token_expire' => $this->now,
        ]);
        self::assertFalse($user->isPhoneVerified());
        $user->verifyPhone($token, $this->now->copy()->subSeconds(13), $this->tokenizer->default($user->phone_verify_token));
        self::assertTrue($user->isPhoneVerified());
    }

    public function testVerifyIncorrectToken(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '79000000000',
            'phone_verified' => false,
            'phone_verify_token' => 'token',
            'phone_verify_token_expire' => $this->now,
        ]);
        $this->expectExceptionMessage('Token is invalid.');
        $user->verifyPhone('other_token', $this->now->copy()->subSeconds(13), $this->tokenizer->default($user->phone_verify_token));
    }

    public function testVerifyExpiredToken(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '79000000000',
            'phone_verified' => false,
            'phone_verify_token' => $token = 'token',
            'phone_verify_token_expire' => $this->now,
        ]);
        $this->expectExceptionMessage('Token is expired.');
        $user->verifyPhone($token, $this->now->copy()->addSeconds(500), $this->tokenizer->default($user->phone_verify_token));
    }

    private function tokenizer(): Tokenizer
    {
        return App::make(TokenizerSms::class);
    }
}
