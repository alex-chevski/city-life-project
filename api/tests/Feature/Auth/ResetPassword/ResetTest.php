<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\ResetPassword;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @internal
 */
final class ResetTest extends TestCase
{
    use DatabaseTransactions;

    private Carbon $now;

    protected function setUp(): void
    {
        parent::setUp();
        $this->now = Carbon::now('Europe/Moscow');
    }

    public function testForm(): void
    {
        $response = $this->get('/password/reset/{Str::uuid()->toString}');
        $response
            ->assertStatus(200)
            ->assertSee('Сбросить пароль');
    }

    public function testCorrect(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_ACTIVE, 'verify_token' => Str::uuid()->toString(), 'expires' => $this->now->modify('+5 Minutes')]);
        $response = $this->post('password/reset', [
            'email' => $user->email,
            'token' => $user->verify_token,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response
            ->assertStatus(302)
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Password reset success');
    }

    public function testInvalidToken(): void
    {
        $user = User::factory()->create(['status' => User::STATUS_WAIT]);
        $response = $this->post('password/reset', [
            'email' => $user->email,
            'token' => Str::uuid()->toString(),
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('error', 'Incorrect verify token. ');
    }
}
