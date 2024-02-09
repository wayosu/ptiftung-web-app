<?php

namespace Database\Seeders;

use App\Models\BidangKepakaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidangKepakaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // make 10 random bidang kepakaran
        BidangKepakaran::factory()->count(10)->create();
    }
}
