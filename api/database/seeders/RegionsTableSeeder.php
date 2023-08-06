<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionsTableSeeder extends Seeder
{
    public function run(): void
    {
        Region::factory()->count(10)->create()->each(function(Region $region){
            $region->children()->saveMany(Region::factory()->count(random_int(3, 10))->create()->each(function(Region $region){
                $region->children()->saveMany(Region::factory()->count(random_int(3,10))->make());
            }));
        });

    }
}
