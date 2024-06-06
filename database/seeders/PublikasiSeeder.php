<?php

namespace Database\Seeders;

use App\Models\Publikasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat 100 data dummy
        Publikasi::factory()->count(100)->create();
    }
}
