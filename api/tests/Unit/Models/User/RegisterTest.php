<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\User;

// Test create User for himself(User)
class RegisterTest extends TestCase
{
    private User $user;

    public function setUp():void{

        parent::setUp();

        $this->user = User::register(
            'name', 'email', 'password',
        );
    }

    public function tearDown():void{
        parent::tearDown();
        $this->user->delete();
    }
    public function testRequest()
    {
        self::assertNotEmpty($this->user);

        self::assertEquals('name', $this->user->name);
        self::assertEquals('email', $this->user->email);

        // check hash password
        self::assertNotEmpty('name', $this->user->name);
        self::assertNotEquals('password', $this->user->password);

        // check status
        self::assertTrue($this->user->isWait());
        self::assertFalse($this->user->isActive());

    }

    public function testVerify()
    {
        $this->user->verify();
        self::assertFalse($this->user->isWait());
        self::assertTrue($this->user->isActive());
    }


    public function testAlreadyVerified()
    {
        $this->user->verify();
        $this->expectExceptionMessage('User is already verified.');
        $this->user->verify();
    }

}
