<?php

namespace Database\Seeders;

use App\Models\DataDukungAkreditasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataDukungAkreditasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat 100 data dummy
        DataDukungAkreditasi::factory()->count(100)->create();
    }
}
