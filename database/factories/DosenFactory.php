<?php

namespace Database\Factories;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dosen>
 */
class DosenFactory extends Factory
{
    protected $model = Dosen::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => $this->faker->unique()->numberBetween(100000000, 999999999),
            'program_studi' => $this->faker->randomElement(['SISTEM INFORMASI', 'PEND. TEKNOLOGI INFORMASI']),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'umur' => $this->faker->numberBetween(20, 60),
            'jafa' => $this->faker->randomElement(['Lektor Kepala', 'Lektor', 'Asisten', 'Guru Besar']),
        ];
    }
}
