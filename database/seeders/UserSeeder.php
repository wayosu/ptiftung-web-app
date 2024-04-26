<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat 2 data dummy pengguna dengan role 'admin'
        User::factory()->count(2)->create()->each(function ($user) {
            $user->assignRole('admin');
        });

        // buat 10 data dummy pengguna dengan role 'dosen'
        User::factory()->count(10)->dosen()->create()->each(function ($user) {
            $user->assignRole('dosen');

            // Create a Dosen model for each user
            Dosen::factory()->create([
                'user_id' => $user->id,
                'slug' => Str::slug($user->name),
                'jenis_kelamin' => $faker = \Faker\Factory::create(), // Using Faker directly
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'umur' => $faker->numberBetween(18, 60),
                'jafa' => 'Lektor Kepala'
            ]);
        });

        // buat 10 data dummy pengguna dengan role 'mahasiswa'
        User::factory()->count(10)->mahasiswa()->create()->each(function ($user) {
            $user->assignRole('mahasiswa');

            // buat angkatan yang acak dari 2018-2023
            $angkatan = rand(2018, 2023);

            // buat data dummy ke dalam tabel mahasiswa
            $user->mahasiswa()->create([
                'user_id' => $user->id,
                'program_studi' => 'Pendidikan Teknologi Informasi',
                'angkatan' => $angkatan,
            ]);
        });
    }
}
