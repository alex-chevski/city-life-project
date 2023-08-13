<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @internal
 */
final class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    public function testForm(): void
    {
        $response = $this->get('/register');
        $response
            ->assertStatus(200)
            ->assertSee('Регистрация');
    }

    public function testErrors(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function testSuccess(): void
    {
        $user = User::factory()->make();

        $response = $this->post('/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Check your email and click on the link to verify.');
    }

    /**
     * undocumented function.
     */
    public function testVerifyIncorrect(): void
    {
        $response = $this->get('/verify/' . Str::uuid());

        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('error', 'Sorry your link cannot be identified.');
    }

    public function testVerify(): void
    {
        $user = User::factory()->create([
            'status' => User::STATUS_WAIT,
            'verify_token' => Str::uuid(),
        ]);

        $response = $this->get('/verify/' . $user->verify_token);

        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Your e-mail is verified. You can now login.');
    }
}
