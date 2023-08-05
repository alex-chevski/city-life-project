<?php

declare(strict_types=1);

namespace Tests\Unit\Models\User;

use App\Models\User\User;
use Tests\TestCase;

// Test create new User for Admin
/**
 * @internal
 */
final class CreateTest extends TestCase
{
    public function testNew(): void
    {
        $user = User::factory()->make(['name' => 'name', 'email' => 'email@gmail.com', 'status'=>'active']);

        self::assertNotEmpty($user);

        // name
        self::assertEquals('name', $user->name);

        // email
        self::assertStringContainsString('@', $user->email);
        self::assertEquals('email@gmail.com', $user->email);

        // hash
        self::assertNotEmpty($user->password);

        // status
        self::assertTrue($user->isActive());

        // role
        self::assertFalse($user->isAdmin());
    }
}
