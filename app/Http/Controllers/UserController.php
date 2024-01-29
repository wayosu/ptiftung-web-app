<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        // ambil data
        $users = User::with('roles')->orderBy("created_at", "desc")->get();

        // transformasi data ke bentuk array
        $users = $users->transform(function ($item) {
            $item->role_name = Str::ucfirst($item->roles->pluck('name')->implode(', '));
            return $item;
        })->all();

        // tampilkan view
        return view('admin.pages.users.all-users', [
            'icon' => 'users',
            'title' => 'All Users',
            'subtitle' => 'Daftar seluruh pengguna aplikasi.',
            'active' => 'users',
            'users' => $users
        ]);
    }

    public function destroy($id)
    {
        // cari data berdasarkan id
        $user = User::findOrFail($id);

        // hapus role user
        $user->removeRole($user->roles->pluck('name')->implode(', '));

        // hapus data berdasarkan role
        if ($user->hasRole('mahasiswa')) {
            $user->mahasiswa()->delete();
        } elseif ($user->hasRole('dosen')) {
            $user->dosen()->delete();
        }

        // hapus data user
        $user->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function byAdmin()
    {
        return view('admin.pages.users.admin.index', [
            'icon' => 'users',
            'title' => 'Daftar Admin',
            'subtitle' => 'Daftar seluruh admin.',
            'active' => 'users'
        ]);
    }

    public function createAdmin()
    {
        return view('admin.pages.users.admin.form', [
            'icon' => 'file-text',
            'title' => 'Formulir Tambah Admin',
            'active' => 'users'
        ]);
    }

    public function storeAdmin()
    {
        dd(request()->all());
    }
}
