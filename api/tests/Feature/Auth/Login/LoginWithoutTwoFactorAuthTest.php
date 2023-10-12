<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Login;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @internal
 */
final class LoginWithoutTwoFactorAuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testForm(): void
    {
        $response = $this->get('/login');
        $response
            ->assertStatus(200)
            ->assertSee('Вход');
    }

    public function testErrors(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function testWait(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_WAIT]);
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('error', 'You need to confirm your account. Please check your email.');
    }

    public function testActive(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/cabinet')
            ->assertSessionHasNoErrors();

        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
    }
}
