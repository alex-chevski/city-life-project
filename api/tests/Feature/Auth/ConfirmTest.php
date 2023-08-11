<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @internal
 */
final class ConfirmTest extends TestCase
{
    public function testForm(): void
    {
        $response = $this->get('/password/reset/' . Str::uuid());
        $response
            ->assertStatus(200);
    }
}
