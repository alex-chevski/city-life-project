<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @internal
 */
final class ResetTest extends TestCase
{
    use DatabaseTransactions;

    public function testForm(): void
    {
        $response = $this->get('/password/reset');
        $response
            ->assertStatus(200)
            ->assertSee('Восстановление пароля');
    }

    public function testNotFoundEmail(): void
    {
        $user = User::factory()->make();
        $response = $this->post('password/email', [
            'email' => $user->email,
        ]);
        $response
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('error', 'User is not found.');
    }

    public function testIsNotActiveUser(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_WAIT]);

        $response = $this->post('password/email', [
            'email' => $user->email,
        ]);

        $response
            ->assertRedirect('/')
            ->assertSessionHas('error', 'User is not active.');
    }

    public function testCorrectEmail(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
        $response = $this->post('password/email', [
            'email' => $user->email,
        ]);
        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Check your email and click on the link to reset password.');
    }

    public function testManyRequestToReset(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE]);

        $this->post('password/email', [
            'email' => $user->email,
        ]);

        $response = $this->post('password/email', [
            'email' => $user->email,
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('error', 'Resetting is already requested. ');
    }
}
