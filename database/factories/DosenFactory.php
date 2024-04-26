<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dosen>
 */
class DosenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'umur' => $this->faker->numberBetween(18, 60),
            'jafa' => 'Lektor Kepala'
        ];
    }
}
