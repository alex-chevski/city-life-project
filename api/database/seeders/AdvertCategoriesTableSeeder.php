<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Adverts\Category;
use Illuminate\Database\Seeder;

class AdvertCategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        Category::factory()->count(10)->create()->each(static function (Category $category): void {
            $counts = [0, random_int(3, 7)];
            $category->children()->saveMany(Category::factory()->count($counts[array_rand($counts)])->create()->each(static function (Category $category) use ($counts): void {
                $category->children()->saveMany(Category::factory()->count($counts[array_rand($counts)])->create());
            }));
        });
    }
}
