<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BidangKepakaran;
use App\Models\Mahasiswa;
use App\Models\Pendidikan;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // ambil data
            $users = User::with('roles')->orderBy("created_at", "desc")->get();

            // transformasi data ke bentuk array
            $users = $users->transform(function ($item) {
                $item->role_name = Str::ucfirst($item->roles->pluck('name')->implode(', '));
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($users)
                ->addColumn('aksi', function ($users) {
                    return view('admin.pages.users.tombol-aksi', compact('users'));
                })
                ->make(true);
        }

        // tampilkan view
        return view('admin.pages.users.index', [
            'icon' => 'users',
            'title' => 'Semua Pengguna',
            'subtitle' => 'Daftar seluruh pengguna aplikasi.',
            'active' => 'users',
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
            $user->dosen->pendidikan()->delete();
            $user->dosen->bidangKepakaran()->detach();
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

    public function byAdmin(Request $request)
    {
        if ($request->ajax()) {
            // ambil data
            $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })->orderBy("created_at", "desc")->get();

            // transformasi data ke bentuk array
            $users = $users->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($users)
                ->addColumn('aksi', function ($users) {
                    return view('admin.pages.users.admin.tombol-aksi', compact('users'));
                })
                ->make(true);
        }

        // tampilkan view
        return view('admin.pages.users.admin.index', [
            'icon' => 'users',
            'title' => 'Admin',
            'subtitle' => 'Daftar seluruh admin.',
            'active' => 'admin',
        ]);
    }

    public function createAdmin()
    {
        return view('admin.pages.users.admin.form', [
            'icon' => 'plus',
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
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa jpeg, png, jpg.',
            'foto.max' => 'Foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

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
            'icon' => 'edit',
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

            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

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

    public function byDosen(Request $request)
    {
        if ($request->ajax()) {
            // ambil data
            $users = User::with('dosen')->whereHas('roles', function ($q) {
                $q->where('name', 'dosen');
            })->orderBy("name", "asc")->get();

            // transformasi data ke bentuk array
            $users = $users->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($users)
                ->addColumn('aksi', function ($users) {
                    return view('admin.pages.users.dosen.tombol-aksi', compact('users'));
                })
                ->make(true);
        }

        return view('admin.pages.users.dosen.index', [
            'icon' => 'users',
            'title' => 'Dosen',
            'subtitle' => 'Daftar seluruh dosen.',
            'active' => 'dosen',
        ]);
    }
    public function createDosen()
    {
        // ambil data bidang kepakaran
        $bidangKepakarans = BidangKepakaran::orderBy('id', 'asc')->get();

        return view('admin.pages.users.dosen.form', [
            'icon' => 'plus',
            'title' => 'Dosen',
            'subtitle' => 'Tambah Dosen',
            'active' => 'dosen',
            'bidangKepakarans' => $bidangKepakarans
        ]);
    }

    public function storeDosen(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'gelar' => 'required',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'umur' => 'required|numeric',
            'nip' => 'required|numeric|unique:users,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'gelar.required' => 'Gelar harus diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'umur.required' => 'Umur harus diisi.',
            'umur.numeric' => 'Umur harus berupa angka.',
            'nip.required' => 'NIP harus diisi.',
            'nip.numeric' => 'NIP harus berupa angka.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa jpeg, png, jpg.',
            'foto.max' => 'Foto maksimal 2MB.',
        ]);

        $dataUser = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'nip' => $request->nip,
        ];

        if ($request->biografi) {
            $dataDosen['biografi'] = $request->biografi;
        }

        if ($request->minat_penelitian) {
            $dataDosen['minat_penelitian'] = $request->minat_penelitian;
        }

        if ($request->link_scopus) {
            $dataDosen['link_scopus'] = $request->link_scopus;
        }

        if ($request->link_sinta) {
            $dataDosen['link_sinta'] = $request->link_sinta;
        }

        if ($request->link_gscholar) {
            $dataDosen['link_gscholar'] = $request->link_gscholar;
        }

        if ($request->hasFile('foto')) {
            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();
            $request->file('foto')->storeAs('public/usersProfile', $nameFile);
            $dataUser['foto'] = $nameFile;
        }

        $user = User::create($dataUser);
        $user->assignRole('dosen');

        $dataDosen = [
            'slug' => Str::slug($request->name),
            'jenis_kelamin' => $request->jenis_kelamin,
            'umur' => $request->umur,
            'gelar' => $request->gelar,
        ];

        $dosen = $user->dosen()->create($dataDosen);

        if ($request->pendidikan) {
            $dosen->pendidikan()->createMany(
                array_map(function ($pendidikan) {
                    return ['pendidikan' => $pendidikan];
                }, $request->pendidikan)
            );
        }

        if ($request->bidang_kepakaran) {
            $dosen->bidangKepakaran()->attach($request->bidang_kepakaran);
        }

        return redirect()->route('users.byDosen')->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function editDosen($id)
    {
    }

    public function updateDosen(Request $request, $id)
    {
    }

    public function byMahasiswa(Request $request)
    {
        if ($request->ajax()) {
            // ambil data
            $users = User::with('mahasiswa')->whereHas('roles', function ($q) {
                $q->where('name', 'mahasiswa');
            })->orderBy("nim", "asc")->get();

            // transformasi data ke bentuk array
            $users = $users->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($users)
                ->addColumn('aksi', function ($users) {
                    return view('admin.pages.users.mahasiswa.tombol-aksi', compact('users'));
                })
                ->make(true);
        }

        return view('admin.pages.users.mahasiswa.index', [
            'icon' => 'users',
            'title' => 'Mahasiswa',
            'subtitle' => 'Daftar seluruh mahasiswa.',
            'active' => 'mahasiswa',
        ]);
    }

    public function createMahasiswa()
    {
        return view('admin.pages.users.mahasiswa.form', [
            'icon' => 'plus',
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
            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

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
            'mahasiswa_id' => $user->id,
            'program_studi' => $request->program_studi,
            'angkatan' => $request->angkatan,
        ]);

        return redirect()->route('users.byMahasiswa')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function editMahasiswa($id)
    {
        $user = User::with('mahasiswa')->findOrFail($id);

        return view('admin.pages.users.mahasiswa.form', [
            'icon' => 'edit',
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

            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

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
