<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DokumenKebijakan>
 */
class DokumenKebijakanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_studi' => $this->faker->randomElement(['SISTEM INFORMASI', 'PEND. TEKNOLOGI INFORMASI']),
            'keterangan' => $this->faker->sentence(10),
            'kategori' => $this->faker->randomElement(['Pendidikan', 'Penelitian', 'Pengabdian', 'Kemahasiswaan', 'Kerja Sama', 'Tata Kelola']),
            'dokumen' => null,
            'link_dokumen' => $this->faker->url,
            'created_by' => 1,
            'updated_by' => null
        ];
    }
}
