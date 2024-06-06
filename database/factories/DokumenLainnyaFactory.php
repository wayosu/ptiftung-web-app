<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DokumenLainnya>
 */
class DokumenLainnyaFactory extends Factory
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
            'dokumen' => null,
            'link_dokumen' => $this->faker->url,
            'created_by' => 1,
            'updated_by' => null
        ];
    }
}
