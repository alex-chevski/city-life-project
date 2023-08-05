<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Str;

// Test create User for himself(User)
/**
 * @internal
 */
final class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['name' => 'name', 'email' => 'email', 'password' => Str::uuid(), 'status' => 'wait', 'role' => 'user']);
    }

    public function testRequest(): void
    {
        self::assertNotEmpty($this->user);

        self::assertEquals('name', $this->user->name);
        self::assertEquals('email', $this->user->email);

        self::assertNotEmpty('name', $this->user->name);
        self::assertNotEquals('password', $this->user->password);

        self::assertTrue($this->user->isWait());
        self::assertFalse($this->user->isActive());

        // role
        self::assertFalse($this->user->isAdmin());
    }

    public function testVerify(): void
    {
        $this->user->verify();
        self::assertFalse($this->user->isWait());
        self::assertTrue($this->user->isActive());
    }

    public function testAlreadyVerified(): void
    {
        $this->user->verify();
        $this->expectExceptionMessage('User is already verified.');
        $this->user->verify();
    }
}
