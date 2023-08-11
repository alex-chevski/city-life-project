<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    public function run(): void
    {
        Region::factory()->count(10)->create()->each(static function (Region $region): void {
            $region->children()->saveMany(Region::factory()->count(random_int(3, 10))->create()->each(static function (Region $region): void {
                $region->children()->saveMany(Region::factory()->count(random_int(3, 10))->make());
            }));
        });
    }
}
