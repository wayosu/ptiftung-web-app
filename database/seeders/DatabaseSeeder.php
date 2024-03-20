<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // panggil semua seeder
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            BidangKepakaranSeeder::class,
            ProfilProgramStudiSeeder::class,
        ]);
    }
}
