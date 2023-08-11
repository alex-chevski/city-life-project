<?php

declare(strict_types=1);

namespace Unit\Models\User;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class VerifyMailTest.
 * @author yourname
 * @internal
 */
final class VerifyMailTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['status' => 'wait', 'role' => 'user']);
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
