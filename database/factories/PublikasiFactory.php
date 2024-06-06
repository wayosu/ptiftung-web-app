<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publikasi>
 */
class PublikasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dosen_id' => $this->faker->numberBetween(1, 10),
            'judul' => $this->faker->sentence(3),
            'link_publikasi' => $this->faker->url,
            'created_by' => 1,
            'updated_by' => null
        ];
    }
}
