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

// Test create User for himself(User)
/**
 * @internal
 */
final class RequestTest extends TestCase
{
    use DatabaseTransactions;

    private $tokenizer;
    private $now;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenizer = $this->tokenizer();
        $this->now = Carbon::now();
    }

    public function testDefault(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => null,
            'phone_verified' => false,
            'phone_verify_token' => null,
        ]);
        self::assertFalse($user->isPhoneVerified());
    }

    public function testRequestEmptyPhone(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => null,
            'phone_verified' => false,
            'phone_verify_token' => null,
        ]);
        $this->expectExceptionMessage('Phone number is empty.');
        $user->requestPhoneVerification($this->tokenizer, $this->now);
    }

    public function testRequest(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '79000000000',
            'phone_verified' => false,
            'phone_verify_token' => null,
        ]);
        $user->requestPhoneVerification($this->tokenizer, $this->now);
        self::assertFalse($user->isPhoneVerified());
        self::assertNotEmpty($user->phone_verify_token);
    }

    public function testRequestWithOldPhone(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '79000000000',
            'phone_verified' => true,
            'phone_verify_token' => null,
        ]);

        self::assertTrue($user->isPhoneVerified());

        $user->requestPhoneVerification($this->tokenizer, $this->now);
        self::assertFalse($user->isPhoneVerified());
        self::assertNotEmpty($user->phone_verify_token);
    }

    public function testRequestAlreadySentTimeout(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '79000000000',
            'phone_verified' => true,
            'phone_verify_token' => null,
        ]);
        $user->requestPhoneVerification($this->tokenizer, $this->now);
        $user->requestPhoneVerification($this->tokenizer, $this->now->copy()->addSeconds(500));
        self::assertFalse($user->isPhoneVerified());
    }

    public function testRequestAlreadySent(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '79000000000',
            'phone_verified' => true,
            'phone_verify_token' => null,
            'phone_verify_token_expire' => null,
        ]);

        $user->requestPhoneVerification($this->tokenizer, $this->now->copy());

        $this->expectExceptionMessage('Resetting is already requested. ');
        $user->requestPhoneVerification($this->tokenizer, $this->now->copy()->addSeconds(15));
    }

    private function tokenizer(): Tokenizer
    {
        return App::make(TokenizerSms::class);
    }
}
