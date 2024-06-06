<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat 3 data role
        Role::create(['name' => 'Superadmin']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Kajur']);
        Role::create(['name' => 'Kaprodi']);
        Role::create(['name' => 'Dosen']);
        Role::create(['name' => 'Mahasiswa']);
    }
}
