<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User\PhoneVerify;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// Test create User for himself(User)
/**
 * @internal
 */
final class HasFilledProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function testSuccess(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '79000000000',
            'phone_verified' => true,
            'phone_verify_token' => null,
        ]);

        self::assertTrue($user->hasFilledProfile());
    }

    public function testIsEmptyProfile(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => null,
            'phone_verified' => false,
            'phone_verify_token' => null,
            'phone_auth' => false,
        ]);

        self::assertFalse($user->hasFilledProfile());
    }
}
