<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Adverts\Category;

class AdvertCategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        Category::factory()->count(10)->create()->each(function(Category $category){
            $counts = [0, random_int(3, 7)];
            $category->children()->saveMany(Category::factory()->count($counts[array_rand($counts)])->create()->each(function(Category $category) use($counts){
                $category->children()->saveMany(Category::factory()->count($counts[array_rand($counts)])->create());
            }));
        });

    }
}
