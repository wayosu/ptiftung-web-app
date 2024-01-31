<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::factory()->create()->assignRole('admin');

        // Create 10 users with 'dosen' role
        User::factory()->count(10)->dosen()->create()->each(function ($user) {
            $user->assignRole('dosen');
        });

        // Create 10 users with 'mahasiswa' role
        User::factory()->count(10)->mahasiswa()->create()->each(function ($user) {
            $user->assignRole('mahasiswa');

            $user->mahasiswa()->create([
                'program_studi' => 'Pendidikan Teknologi Informasi',
                'angkatan' => 2020,
            ]);
        });
    }
}
