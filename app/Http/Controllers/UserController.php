<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
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
            'title' => 'Admin',
            'subtitle' => 'Daftar seluruh admin.',
            'active' => 'admin',
            'users' => $users
        ]);
    }

    public function createAdmin()
    {
        return view('admin.pages.users.admin.form', [
            'icon' => 'users',
            'title' => 'Admin',
            'subtitle' => 'Tambah Admin',
            'active' => 'admin'
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
            'icon' => 'users',
            'title' => 'Admin',
            'subtitle' => 'Edit Admin',
            'active' => 'admin',
            'user' => $user
        ]);
    }

    public function updateAdmin(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $id,
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        if ($request->password) {
            $request->validate([
                'password' => 'min:6'
            ], [
                'password.min' => 'Password minimal 6 karakter.',
            ]);
        }

        $user = User::findOrFail($id);

        if ($request->hasFile('foto')) {
            if (Storage::exists('public/usersProfile/' . $user->foto)) {
                Storage::delete('public/usersProfile/' . $user->foto);
            }

            $extractEmail = explode('@', $request->email);
            $nameFile = $extractEmail[0] . '-' .  $request->file('foto')->hashName();

            $request->file('foto')->storeAs('public/usersProfile', $nameFile);

            $dataUser = [
                'name' => $request->name,
                'email' => $request->email,
                'foto' => $nameFile
            ];
        } else {
            $dataUser = [
                'name' => $request->name,
                'email' => $request->email
            ];
        }

        if ($request->password) {
            $dataUser['password'] = bcrypt($request->password);
        }

        $user->update($dataUser);

        return redirect()->route('users.byAdmin')->with('success', 'Data admin berhasil diubah.');
    }

    public function byDosen()
    {
        return view('admin.pages.users.dosen.index', [
            'icon' => 'users',
            'title' => 'Dosen',
            'subtitle' => 'Daftar seluruh dosen.',
            'active' => 'dosen',
        ]);
    }
    public function createDosen()
    {
        return view('admin.pages.users.dosen.form', [
            'icon' => 'users',
            'title' => 'Dosen',
            'subtitle' => 'Tambah Dosen',
            'active' => 'dosen'
        ]);
    }

    public function byMahasiswa()
    {
        // ambil data
        $users = User::with('mahasiswa')->whereHas('roles', function ($q) {
            $q->where('name', 'mahasiswa');
        })->orderBy("nim", "asc")->get();

        return view('admin.pages.users.mahasiswa.index', [
            'icon' => 'users',
            'title' => 'Mahasiswa',
            'subtitle' => 'Daftar seluruh mahasiswa.',
            'active' => 'mahasiswa',
            'users' => $users
        ]);
    }

    public function createMahasiswa()
    {
        return view('admin.pages.users.mahasiswa.form', [
            'icon' => 'users',
            'title' => 'Mahasiswa',
            'subtitle' => 'Tambah Mahasiswa',
            'active' => 'mahasiswa'
        ]);
    }

    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'nim' => 'required|numeric|unique:users,nim',
            'program_studi' => 'required',
            'angkatan' => 'required|numeric',
            'password' => 'required|min:6',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'nim.required' => 'NIM harus diisi.',
            'nim.numeric' => 'NIM harus berupa angka.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'program_studi.required' => 'Program Studi harus diisi.',
            'angkatan.required' => 'Angkatan harus diisi.',
            'angkatan.numeric' => 'Angkatan harus berupa angka.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa jpeg, png, jpg.',
            'foto.max' => 'Foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $nameFile = $request->nim . '-' .  $request->file('foto')->hashName();

            $request->file('foto')->storeAs('public/usersProfile', $nameFile);

            $user = User::create([
                'name' => $request->name,
                'nim' => $request->nim,
                'password' => bcrypt($request->password),
                'foto' => $nameFile,
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'nim' => $request->nim,
                'password' => bcrypt($request->password),
            ]);
        }

        $user->assignRole('mahasiswa');

        Mahasiswa::create([
            'user_id' => $user->id,
            'program_studi' => $request->program_studi,
            'angkatan' => $request->angkatan,
        ]);

        return redirect()->route('users.byMahasiswa')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function editMahasiswa($id)
    {
        $user = User::with('mahasiswa')->findOrFail($id);

        return view('admin.pages.users.mahasiswa.form', [
            'icon' => 'users',
            'title' => 'Mahasiswa',
            'subtitle' => 'Edit Mahasiswa',
            'active' => 'mahasiswa',
            'user' => $user
        ]);
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'nim' => 'required|numeric|unique:users,nim,' . $id,
            'program_studi' => 'required',
            'angkatan' => 'required|numeric',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'nim.required' => 'NIM harus diisi.',
            'nim.numeric' => 'NIM harus berupa angka.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'program_studi.required' => 'Program Studi harus diisi.',
            'angkatan.required' => 'Angkatan harus diisi.',
            'angkatan.numeric' => 'Angkatan harus berupa angka.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa jpeg, png, jpg.',
            'foto.max' => 'Foto maksimal 2MB.',
        ]);

        if ($request->password) {
            $request->validate([
                'password' => 'min:6'
            ], [
                'password.min' => 'Password minimal 6 karakter.',
            ]);
        }

        $user = User::with('mahasiswa')->findOrFail($id);

        if ($request->hasFile('foto')) {
            if (Storage::exists('public/usersProfile/' . $user->foto)) {
                Storage::delete('public/usersProfile/' . $user->foto);
            }

            $nameFile = $request->nim . '-' .  $request->file('foto')->hashName();

            $request->file('foto')->storeAs('public/usersProfile', $nameFile);

            $dataUser = [
                'name' => $request->name,
                'nim' => $request->nim,
                'foto' => $nameFile
            ];
        } else {
            $dataUser = [
                'name' => $request->name,
                'nim' => $request->nim,
            ];
        }

        if ($request->password) {
            $dataUser['password'] = bcrypt($request->password);
        }

        $user->update($dataUser);

        $user->mahasiswa()->update([
            'program_studi' => $request->program_studi,
            'angkatan' => $request->angkatan,
        ]);

        return redirect()->route('users.byMahasiswa')->with('success', 'Data mahasiswa berhasil diubah.');
    }
}
