<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    protected $model = Mahasiswa::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nim' => $this->faker->unique()->numberBetween(100000000, 999999999),
            'program_studi' => $this->faker->randomElement(['SISTEM INFORMASI', 'PEND. TEKNOLOGI INFORMASI']),
            'angkatan' => $this->faker->numberBetween(2018, 2023),
        ];
    }
}
