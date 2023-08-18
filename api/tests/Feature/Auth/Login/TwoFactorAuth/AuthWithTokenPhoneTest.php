<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User\User;
use App\Services\Auth\Tokenizer\TokenizerSms;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

/**
 * @internal
 */
final class AuthWithTokenPhoneTest extends TestCase
{
    use DatabaseTransactions;

    private $now;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->now = Carbon::now('Europe/Moscow');
        $this->token = $this->generate($this->now);
    }

    public function testSuccess(): void
    {
        $user = User::factory()->create(
            [
                'status' => User::STATUS_ACTIVE,
                'phone' => '79000000000',
                'phone_verified' => true,
                'phone_auth' => true,
                'phone_verify_token' => $this->token->getValue(),
                'phone_verify_token_expire' => $this->token->getExpires(),
            ]
        );

        $response = $this
            ->withSession(['auth' => [
                'id' => $user->id,
                'token' => $user->phone_verify_token,
                'remember' => false,
            ]])
            ->post('login/phone', [
                'token' => $user->phone_verify_token,
            ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/cabinet')
            ->assertSessionHasNoErrors();
    }

    public function testIncorrectTokenInput(): void
    {
        $user = User::factory()->create(
            [
                'status' => User::STATUS_ACTIVE,
                'phone' => '79000000000',
                'phone_verified' => true,
                'phone_auth' => true,
                'phone_verify_token' => $this->token->getValue(),
                'phone_verify_token_expire' => $this->token->getExpires(),
            ]
        );

        $response = $this
            ->withSession(['auth' => [
                'id' => $user->id,
                'token' => $user->phone_verify_token,
                'remember' => false,
            ]])
            ->post('login/phone', [
                'token' => (string)random_int(10000, 99999),
            ]);

        $response
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHasErrors(['token']);
    }

    private function generate()
    {
        return App::make(TokenizerSms::class)->generate($this->now->copy());
    }
}
