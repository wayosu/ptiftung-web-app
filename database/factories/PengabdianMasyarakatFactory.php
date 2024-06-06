<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengabdianMasyarakat>
 */
class PengabdianMasyarakatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tahun' => $this->faker->numberBetween(2018, 2023),
            'dosen_id' => $this->faker->numberBetween(1, 10),
            'jabatan' => 'Ketua',
            'skim' => '-',
            'judul' => $this->faker->sentence(3),
            'sumber_dana' => 'UNG',
            'jumlah_dana' => number_format($this->faker->numberBetween(1000000, 1000000000), 0, ',', '.'),
            'created_by' => 1,
            'updated_by' => null
        ];
    }
}
