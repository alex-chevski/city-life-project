<?php

declare(strict_types=1);

namespace Database\Factories\Adverts;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adverts\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name,
            'slug' => $this->faker->unique()->slug(2),
            'parent_id' => null,
        ];
    }
}
