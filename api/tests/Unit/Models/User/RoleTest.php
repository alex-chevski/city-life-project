<?php

declare(strict_types=1);

namespace Unit\Models\User;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @author yourname
 * @internal
 */
final class RoleTest extends TestCase
{
    use DatabaseTransactions;

    public function testChange(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_USER]);

        self::assertFalse($user->isAdmin());

        $user->changeRole(User::ROLE_ADMIN);

        self::assertTrue($user->isAdmin());
    }

    /**
     * undocumented function.
     */
    public function testAlready(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);

        self::expectExceptionMessage('Role is already assigned.');

        $user->changeRole(User::ROLE_ADMIN);
    }
}
