<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'foto' => null,
        ];
    }

    // Indicate that the user has the 'dosen' role
    public function dosen(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => null, // Set email to null for 'dosen'
            'nip' => $this->faker->unique()->numberBetween(100000000, 999999999),
        ]);
    }

    // Indicate that the user has the 'mahasiswa' role
    public function mahasiswa(): static
    {
        return $this->state(fn (array $attributes) => [
            'email' => null, // Set email to null for 'mahasiswa'
            'nim' => $this->faker->unique()->numberBetween(100000000, 999999999),
        ]);
    }
}
