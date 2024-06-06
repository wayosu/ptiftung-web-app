<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataDukungAkreditasi>
 */
class DataDukungAkreditasiFactory extends Factory
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
            'nomor_butir' => $this->faker->numberBetween(1, 100),
            'keterangan' => $this->faker->sentence(3),
            'kategori' => $this->faker->randomElement(['UPPS', 'Program Studi', 'Dokumentasi Video']),
            'dokumen' => null,
            'link_dokumen' => $this->faker->url,
            'created_by' => 1,
            'updated_by' => null
        ];
    }
}
