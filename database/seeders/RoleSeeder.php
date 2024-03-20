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
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'dosen']);
        Role::create(['name' => 'mahasiswa']);
    }
}
