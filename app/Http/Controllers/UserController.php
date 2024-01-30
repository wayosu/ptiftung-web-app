<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
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

        // hapus foto storage
        if (Storage::exists('public/usersProfile/' . $user->foto)) {
            Storage::delete('public/usersProfile/' . $user->foto);
        }

        // hapus data user
        $user->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function byAdmin()
    {
        // ambil data
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->orderBy("created_at", "desc")->get();

        // tampilkan view
        return view('admin.pages.users.admin.index', [
            'icon' => 'users',
            'title' => 'Daftar Admin',
            'subtitle' => 'Daftar seluruh admin.',
            'active' => 'users',
            'users' => $users
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

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if ($request->hasFile('foto')) {
            $extractEmail = explode('@', $request->email);
            $nameFile = $extractEmail[0] . '-' .  $request->file('foto')->hashName();

            $request->file('foto')->storeAs('public/usersProfile', $nameFile);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'foto' => $nameFile
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        }

        $user->assignRole('admin');

        return redirect()->route('users.byAdmin')->with('success', 'Data admin berhasil ditambahkan.');
    }

    public function editAdmin($id)
    {
        $user = User::findOrFail($id);

        return view('admin.pages.users.admin.form', [
            'icon' => 'file-text',
            'title' => 'Formulir Edit Admin',
            'active' => 'users',
            'user' => $user
        ]);
    }

    public function updateAdmin(Request $request, $id)
    {
    }
}
