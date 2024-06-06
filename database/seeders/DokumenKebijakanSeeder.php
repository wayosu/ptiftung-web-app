<?php

namespace Database\Seeders;

use App\Models\DokumenKebijakan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DokumenKebijakanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat 100 data dummy
        DokumenKebijakan::factory()->count(100)->create();
    }
}
