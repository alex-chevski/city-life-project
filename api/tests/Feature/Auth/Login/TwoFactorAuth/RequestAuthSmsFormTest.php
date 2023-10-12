<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Login\TwoFactorAuth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @internal
 */
final class RequestAuthSmsFormTest extends TestCase
{
    use DatabaseTransactions;

    public function testForm(): void
    {
        $response = $this->get('/login');
        $response
            ->assertStatus(200)
            ->assertSee('Вход');
    }

    public function testSuccess(): void
    {
        $user = User::factory()->create(
            [
                'status' => User::STATUS_ACTIVE,
                'phone' => '79000000000',
                'phone_verified' => true,
                'phone_auth' => true,
                'phone_verify_token' => null,
                'phone_verify_token_expire' => null,
            ]
        );

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/login/phone')
            ->assertSessionHasAll(['auth']);
    }

    public function testRepeatRequestForm(): void
    {
        $user = User::factory()->create(
            [
                'status' => User::STATUS_ACTIVE,
                'phone' => '79000000000',
                'phone_verified' => true,
                'phone_auth' => true,
                'phone_verify_token' => null,
                'phone_verify_token_expire' => null,
            ]
        );

        $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHas('error', 'Resetting is already requested. ');
    }

    public function testUnverifiedNewNumberPhone(): void
    {
        $user = User::factory()->create(
            [
                'status' => User::STATUS_ACTIVE,
                'phone' => '79000000000',
                'phone_verified' => false,
                'phone_auth' => true,
                'phone_verify_token' => null,
                'phone_verify_token_expire' => null,
            ]
        );

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertSessionHas('error', 'Your phone number is not verified. ');
    }
}
