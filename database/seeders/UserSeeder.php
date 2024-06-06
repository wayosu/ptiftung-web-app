<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
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
        // buat 1 data dummy pengguna dengan role 'super-admin'
        User::factory()->count(1)->create()->each(function ($user) {
            $user->assignRole('Superadmin');
        });

        // buat 1 data dummy pengguna dengan role 'admin'
        User::factory()->count(1)->create()->each(function ($user) {
            $user->assignRole('Admin');
        });

        // buat 1 data dummy pengguna dengan role 'Kajur'
        User::factory()->count(1)->create()->each(function ($user) {
            $user->assignRole('Kajur');

            // Create a Dosen model for each user
            Dosen::factory()->create([
                'user_id' => $user->id,
                'slug' => Str::slug($user->name),
            ]);
        });

        // buat 1 data dummy pengguna dengan role 'kepala-program-studi'
        User::factory()->count(2)->create()->each(function ($user) {
            $user->assignRole('Kaprodi');

            // Create a Dosen model for each user
            Dosen::factory()->create([
                'user_id' => $user->id,
                'slug' => Str::slug($user->name),
            ]);
        });

        // buat 20 data dummy pengguna dengan role 'dosen'
        User::factory()->count(20)->create()->each(function ($user) {
            $user->assignRole('Dosen');

            // Create a Dosen model for each user
            Dosen::factory()->create([
                'user_id' => $user->id,
                'slug' => Str::slug($user->name),
            ]);
        });

        // buat 20 data dummy pengguna dengan role 'mahasiswa'
        User::factory()->count(20)->create()->each(function ($user) {
            $user->assignRole('Mahasiswa');

            // buat data dummy ke dalam tabel mahasiswa
            Mahasiswa::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
