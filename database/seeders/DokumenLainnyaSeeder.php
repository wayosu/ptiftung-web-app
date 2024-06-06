<?php

namespace Database\Seeders;

use App\Models\DokumenLainnya;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DokumenLainnyaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat 100 data dummy
        DokumenLainnya::factory()->count(100)->create();
    }
}
