<?php

namespace Database\Seeders;

use App\Models\ProfilProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProfilProgramStudi::create([
            'nama_program_studi' => 'Pendidikan Teknologi Informasi',
            'nama_dasbor' => 'Dasbor WEB PTI',
            'program_studi' => 'PEND. TEKNOLOGI INFORMASI',
        ]);

        ProfilProgramStudi::create([
            'nama_program_studi' => 'Sistem Informasi',
            'nama_dasbor' => 'Dasbor WEB SI',
            'program_studi' => 'SISTEM INFORMASI',
        ]);
    }
}
