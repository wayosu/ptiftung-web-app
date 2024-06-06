<?php

namespace Database\Seeders;

use App\Models\Penelitian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenelitianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat 100 data dummy
        Penelitian::factory()->count(100)->create();
    }
}
