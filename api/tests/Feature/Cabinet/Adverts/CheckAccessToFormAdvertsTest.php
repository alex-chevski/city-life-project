<?php

declare(strict_types=1);

namespace Tests\Feature\Cabinet\Adverts;

use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// Test create User for himself(User)
/**
 * @internal
 */
final class CheckAccessToFormAdvertsTest extends TestCase
{
    use DatabaseTransactions;

    public function testIsNotHasFilledProfile(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'alex',
            'last_name' => null,
            'phone' => null,
            'phone_verified' => false,
        ]);

        $response = $this->actingAs($user)
            ->get('/cabinet/adverts');

        $response
            ->assertRedirect(route('cabinet.profile.home'))
            ->assertSessionHas('error', 'Please fill your profile and verify your phone.');
    }

    public function testSuccess(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '79000000000',
            'phone_verified' => true,
        ]);

        $response = $this->actingAs($user)
            ->get('/cabinet/adverts');

        $response
            ->assertOk();
    }

    public function testIsNotVerifiedPhone(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '79000000000',
            'phone_verified' => false,
        ]);

        $response = $this->actingAs($user)
            ->get('/cabinet/adverts');

        $response
            ->assertRedirect(route('cabinet.profile.home'))
            ->assertSessionHas('error', 'Please fill your profile and verify your phone.');
    }
}
