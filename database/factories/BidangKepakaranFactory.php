<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BidangKepakaran>
 */
class BidangKepakaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bidang_kepakaran' => $this->faker->word,
            'slug' => $this->faker->slug,
            'created_by' => 1,
            'updated_by' => null
        ];
    }
}
