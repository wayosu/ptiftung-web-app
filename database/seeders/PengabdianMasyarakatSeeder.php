<?php

namespace Database\Seeders;

use App\Models\PengabdianMasyarakat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengabdianMasyarakatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat 100 data dummy
        PengabdianMasyarakat::factory()->count(100)->create();
    }
}
