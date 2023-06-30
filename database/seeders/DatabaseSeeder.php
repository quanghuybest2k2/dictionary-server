<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MeanSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\WordSeeder;
use Database\Seeders\WordTypeSeeder;
use Database\Seeders\SpecializationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SpecializationSeeder::class,
            WordTypeSeeder::class,
            WordSeeder::class,
            MeanSeeder::class,
        ]);
    }
}
