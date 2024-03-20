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
        // jika ada request ajax 
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

        // tampilkan halaman
        return view('admin.pages.users.index', [
            'icon' => 'users',
            'title' => 'Semua Pengguna',
            'subtitle' => 'Daftar seluruh pengguna aplikasi.',
            'active' => 'users',
        ]);
    }

    public function formResetPassword($id)
    {
        $user = User::findOrFail($id);

        // dapatkan peran pengguna
        $user->role_name = Str::ucfirst($user->roles->pluck('name')->implode(', '));

        // ambil data dalam bentuk array berdasarkan peran
        if ($user->role_name == "Admin") {
            $user = $user->only('id', 'name', 'email', 'role_name', 'foto');
        } else if ($user->role_name == "Dosen") {
            $user = $user->only('id', 'name', 'nip', 'role_name', 'foto');
        } else if ($user->role_name == "Mahasiswa") {
            $user = $user->only('id', 'name', 'nim', 'role_name', 'foto');
        } else {
            abort(404);
        }

        // tampilkan halaman
        return view('admin.pages.users.reset-password', [
            'icon' => 'edit',
            'title' => 'Reset Password',
            'subtitle' => 'Reset Password Pengguna',
            'active' => 'users',
            'user' => $user,
        ]);
    }

    public function resetPassword(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'password_baru' => 'required|min:6',
            'konfirmasi_password_baru' => 'same:password_baru',
        ], [
            'password_baru.required' => 'Password baru harus diisi.',
            'password_baru.min' => 'Password baru minimal 6 karakter.',
            'konfirmasi_password_baru.same' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        // cari data berdasarkan id
        $user = User::findOrFail($id);

        // menangani jika user tidak di temukan
        if (!$user) {
            abort(404);
        } else {
            // ubah password
            $user->update([
                'password' => bcrypt($request->password_baru),
            ]);

            // mengalihkan ke halaman sebelumnya
            return redirect()->back()->with('success', 'Password berhasil diatur ulang.');
        }
    }

    public function destroy($id)
    {
        // cari data berdasarkan id
        $user = User::findOrFail($id);

        // hapus peran pengguna
        $user->removeRole($user->roles->pluck('name')->implode(', '));

        // hapus data berdasarkan peran
        if ($user->hasRole('mahasiswa')) {
            $user->mahasiswa()->delete();
        } elseif ($user->hasRole('dosen')) {
            $user->dosen->pendidikan()->delete();
            $user->dosen->bidangKepakaran()->detach();
            $user->dosen()->delete();
        }

        // hapus foto dari storage/penyimpanan
        if (Storage::exists('public/usersProfile/' . $user->foto)) {
            Storage::delete('public/usersProfile/' . $user->foto);
        }

        // hapus data pengguna
        $user->delete();

        // mengalihkan ke halaman sebelumnya
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function byAdmin(Request $request)
    {
        // jika ada request ajax
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

        // tampilkan halaman
        return view('admin.pages.users.admin.index', [
            'icon' => 'users',
            'title' => 'Admin',
            'subtitle' => 'Daftar seluruh admin.',
            'active' => 'admin',
        ]);
    }

    public function createAdmin()
    {
        // tampilkan halaman
        return view('admin.pages.users.admin.form', [
            'icon' => 'plus',
            'title' => 'Admin',
            'subtitle' => 'Tambah Admin',
            'active' => 'admin'
        ]);
    }

    public function storeAdmin(Request $request)
    {
        // validasi data yang dikirim
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

        // jika ada file yang dikirim
        if ($request->hasFile('foto')) {
            // namakan file
            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

            // simpan file ke storage/penyimpanan
            $request->file('foto')->storeAs('public/usersProfile', $nameFile);

            // simpan data
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'foto' => $nameFile
            ]);
        } else {
            // simpan data
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        }

        // tetapkan peran
        $user->assignRole('admin');

        // mengalihkan ke halaman users -> admin -> index 
        return redirect()->route('users.byAdmin')->with('success', 'Data berhasil ditambahkan.');
    }

    public function editAdmin($id)
    {
        // cari data berdasarkan id
        $user = User::findOrFail($id);

        // tampilkan halaman
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
        // validasi data yang dikirim
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

        // cari data berdasarkan id
        $user = User::findOrFail($id);

        // jika ada file yang dikirim
        if ($request->hasFile('foto')) {
            // cek apakah ada file yang lama
            if (Storage::exists('public/usersProfile/' . $user->foto)) {
                // hapus file
                Storage::delete('public/usersProfile/' . $user->foto);
            }

            // namakan file
            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

            // simpan file ke storage/penyimpanan
            $request->file('foto')->storeAs('public/usersProfile', $nameFile);

            // tampung data ke variabel dataUser
            $dataUser = [
                'name' => $request->name,
                'email' => $request->email,
                'foto' => $nameFile
            ];
        } else {
            // tampung data ke variabel dataUser
            $dataUser = [
                'name' => $request->name,
                'email' => $request->email
            ];
        }

        // update data
        $user->update($dataUser);

        // mengalihkan ke halaman users -> admin -> index 
        return redirect()->route('users.byAdmin')->with('success', 'Data berhasil diperbarui.');
    }

    public function byDosen(Request $request)
    {
        // jika ada request ajax
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

        // tampilkan halaman
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
        $bidangKepakarans = BidangKepakaran::orderBy('bidang_kepakaran', 'asc')->get();

        // tampilkan halaman
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
        // validasi data yang dikirim
        $request->validate([
            'name' => 'required|min:3',
            'jafa' => 'required',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'umur' => 'required|numeric',
            'nip' => 'required|numeric|unique:users,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'jafa.required' => 'JAFA (Jabatan Fungsional/Akademik Dosen) harus diisi.',
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

        // tampung data ke variabel dataUser
        $dataUser = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'nip' => $request->nip,
        ];

        // jika ada file yang dikirim
        if ($request->hasFile('foto')) {
            // namakan file
            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

            // simpan file ke storage/penyimpanan
            $request->file('foto')->storeAs('public/usersProfile', $nameFile);

            // simpan data ke variabel dataUser
            $dataUser['foto'] = $nameFile;
        }

        // simpan data
        $user = User::create($dataUser);

        // tetapkan peran
        $user->assignRole('dosen');

        // tampung data ke variabel dataDosen
        $dataDosen = [
            'slug' => Str::slug($request->name),
            'jenis_kelamin' => $request->jenis_kelamin,
            'umur' => $request->umur,
            'jafa' => $request->jafa,
        ];

        // jika ada biografi
        if ($request->biografi) {
            // tampung data ke variabel dataDosen
            $dataDosen['biografi'] = $request->biografi;
        }

        // jika ada minat penelitian
        if ($request->minat_penelitian) {
            // tampung data ke variabel dataDosen
            $dataDosen['minat_penelitian'] = $request->minat_penelitian;
        }

        // jika ada link_scopus
        if ($request->link_scopus) {
            // tampung data ke variabel dataDosen
            $dataDosen['link_scopus'] = $request->link_scopus;
        }

        // jika ada link_sinta
        if ($request->link_sinta) {
            // tampung data ke variabel dataDosen
            $dataDosen['link_sinta'] = $request->link_sinta;
        }

        // jika ada link_gscholar
        if ($request->link_gscholar) {
            // tampung data ke variabel dataDosen
            $dataDosen['link_gscholar'] = $request->link_gscholar;
        }

        // simpan data ke tabel dosen
        $dosen = $user->dosen()->create($dataDosen);

        // jika ada pendidikan
        if ($request->pendidikan) {
            // simpan data ke tabel pendidikan
            $dosen->pendidikans()->createMany(
                array_map(function ($pendidikan) {
                    return ['pendidikan' => $pendidikan];
                }, $request->pendidikan)
            );
        }

        // jika ada bidang kepakaran
        if ($request->bidang_kepakaran) {
            // simpan data ke tabel bidang kepakaran
            $dosen->bidangKepakarans()->attach($request->bidang_kepakaran);
        }

        // mengalihkan ke halaman users -> dosen -> index
        return redirect()->route('users.byDosen')->with('success', 'Data berhasil ditambahkan.');
    }

    public function editDosen($id)
    {
        // cari data berdasarkan peran (dosen) dan id
        $user = User::with('dosen')->whereHas('roles', function ($q) {
            $q->where('name', 'dosen');
        })->where('id', $id)->first();

        // ambil data bidang kepakaran
        $bidangKepakarans = BidangKepakaran::orderBy('bidang_kepakaran', 'asc')->get();

        // tampilkan halaman
        return view('admin.pages.users.dosen.form', [
            'icon' => 'edit',
            'title' => 'Dosen',
            'subtitle' => 'Edit Dosen',
            'active' => 'dosen',
            'user' => $user,
            'bidangKepakarans' => $bidangKepakarans
        ]);
    }

    public function updateDosen(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'name' => 'required|min:3',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'umur' => 'required|numeric',
            'jafa' => 'required',
            'nip' => 'required|numeric|unique:users,nip,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'umur.required' => 'Umur harus diisi.',
            'umur.numeric' => 'Umur harus berupa angka.',
            'jafa.required' => 'JAFA (Jabatan Fungsional/Akademik Dosen) harus diisi.',
            'nip.required' => 'NIP harus diisi.',
            'nip.numeric' => 'NIP harus berupa angka.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa jpeg, png, jpg.',
            'foto.max' => 'Foto maksimal 2MB.',
        ]);

        $user = User::with('dosen')->findOrFail($id);

        // jika ada file yang dikirim
        if ($request->hasFile('foto')) {
            // cek apakah ada file yang lama
            if (Storage::exists('public/usersProfile/' . $user->foto)) {
                // hapus file
                Storage::delete('public/usersProfile/' . $user->foto);
            }

            // namakan file
            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

            // simpan file ke storage/penyimpanan
            $request->file('foto')->storeAs('public/usersProfile', $nameFile);

            // Tampung data ke variabel
            $dataUser = [
                'name' => $request->name,
                'nip' => $request->nip,
                'email' => $request->email,
                'foto' => $nameFile,
            ];
        } else {
            $dataUser = [
                'name' => $request->name,
                'nip' => $request->nip,
                'email' => $request->email,
            ];
        }

        // update data user
        $user->update($dataUser);

        $dataDosen = [
            'slug' => Str::slug($request->name),
            'jenis_kelamin' => $request->jenis_kelamin,
            'umur' => $request->umur,
            'jafa' => $request->jafa,
            'biografi' => $request->biografi,
            'minat_penelitian' => $request->minat_penelitian,
            'link_scopus' => $request->link_scopus,
            'link_sinta' => $request->link_sinta,
            'link_gscholar' => $request->link_gscholar
        ];

        if ($user->dosen) {
            // Update data dosen
            $dosen = $user->dosen;
            $dosen->update($dataDosen);

            // Update data bidang kepakaran
            $dosen->bidangKepakarans()->sync($request->bidang_kepakaran ?? []);

            // Update data pendidikan dosen
            if ($request->pendidikan && is_array($request->pendidikan) && count($request->pendidikan) > 0) {
                $dosen->pendidikans()->delete(); // Hapus semua pendidikan sebelum menambah yang baru

                foreach ($request->pendidikan as $pend) {
                    if ($pend !== null) {
                        $dosen->pendidikans()->create(['pendidikan' => $pend]);
                    }
                }
            } else {
                $dosen->pendidikans()->delete();
            }
        } else {
            // Simpan data ke tabel dosen
            $dosen = $user->dosen()->updateOrCreate([], $dataDosen);

            // Update data bidang kepakaran
            $dosen->bidangKepakarans()->sync($request->bidang_kepakaran ?? []);

            // Update data pendidikan dosen
            if ($request->pendidikan && is_array($request->pendidikan) && count($request->pendidikan) > 0) {
                foreach ($request->pendidikan as $pend) {
                    if ($pend !== null) {
                        $dosen->pendidikans()->create(['pendidikan' => $pend]);
                    }
                }
            }
        }

        // mengalihkan ke halaman users -> dosen -> index
        return redirect()->route('users.byDosen')->with('success', 'Data berhasil diperbarui.');
    }

    public function byMahasiswa(Request $request)
    {
        // jika ada request ajax
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

        // tampilkan halaman
        return view('admin.pages.users.mahasiswa.index', [
            'icon' => 'users',
            'title' => 'Mahasiswa',
            'subtitle' => 'Daftar seluruh mahasiswa.',
            'active' => 'mahasiswa',
        ]);
    }

    public function createMahasiswa()
    {
        // tampilkan halaman
        return view('admin.pages.users.mahasiswa.form', [
            'icon' => 'plus',
            'title' => 'Mahasiswa',
            'subtitle' => 'Tambah Mahasiswa',
            'active' => 'mahasiswa'
        ]);
    }

    public function storeMahasiswa(Request $request)
    {
        // validasi data yang dikirim
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

        // jika ada file yang dikirim
        if ($request->hasFile('foto')) {
            // namakan file
            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

            // simpan file ke storage/penyimpanan
            $request->file('foto')->storeAs('public/usersProfile', $nameFile);

            // simpan data
            $user = User::create([
                'name' => $request->name,
                'nim' => $request->nim,
                'password' => bcrypt($request->password),
                'foto' => $nameFile,
            ]);
        } else {
            // simpan data
            $user = User::create([
                'name' => $request->name,
                'nim' => $request->nim,
                'password' => bcrypt($request->password),
            ]);
        }

        // tetapkan peran
        $user->assignRole('mahasiswa');

        // simpan data ke tabel mahasiswa
        Mahasiswa::create([
            'mahasiswa_id' => $user->id,
            'program_studi' => $request->program_studi,
            'angkatan' => $request->angkatan,
        ]);

        // mengalihkan ke halaman users -> mahasiswa -> index
        return redirect()->route('users.byMahasiswa')->with('success', 'Data berhasil ditambahkan.');
    }

    public function editMahasiswa($id)
    {
        // cari data berdasarkan id
        $user = User::with('mahasiswa')->findOrFail($id);

        // tampilkan halaman
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
        // validasi data yang dikirim
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

        // validasi jika password diisi
        if ($request->password) {
            $request->validate([
                'password' => 'min:6'
            ], [
                'password.min' => 'Password minimal 6 karakter.',
            ]);
        }

        // cari data berdasarkan id
        $user = User::with('mahasiswa')->findOrFail($id);

        // jika ada file yang dikirim
        if ($request->hasFile('foto')) {
            // cek apakah ada file yang lama
            if (Storage::exists('public/usersProfile/' . $user->foto)) {
                // hapus file
                Storage::delete('public/usersProfile/' . $user->foto);
            }

            // namakan file
            $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

            // simpan file ke storage/penyimpanan
            $request->file('foto')->storeAs('public/usersProfile', $nameFile);

            // tampung data ke variabel dataUser
            $dataUser = [
                'name' => $request->name,
                'nim' => $request->nim,
                'foto' => $nameFile
            ];
        } else {
            // tampung data ke variabel dataUser
            $dataUser = [
                'name' => $request->name,
                'nim' => $request->nim,
            ];
        }

        // jika password diisi
        if ($request->password) {
            // tampung data ke variabel dataUser
            $dataUser['password'] = bcrypt($request->password);
        }

        // update data
        $user->update($dataUser);

        // update data mahasiswa
        $user->mahasiswa()->update([
            'program_studi' => $request->program_studi,
            'angkatan' => $request->angkatan,
        ]);

        // mengalihkan ke halaman users -> mahasiswa -> index
        return redirect()->route('users.byMahasiswa')->with('success', 'Data berhasil diperbarui.');
    }
}
