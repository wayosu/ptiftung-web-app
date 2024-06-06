<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BidangKepakaran;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {                 
        // jika ada request ajax 
        if ($request->ajax()) {
            // ambil data
            $users = User::with(['roles', 'dosen'])->whereHas('roles', function($q) {
                $q->where('name', '!=', 'Superadmin');
            })->orderBy("created_at", "desc")->get();

            // transformasi data ke bentuk array
            $users = $users->transform(function ($item) {
                $item->role_names = Str::ucfirst($item->roles->pluck('name')->implode(', '));
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
        try {
            // Cari data user berdasarkan id dengan relasi roles, dosen, dan mahasiswa
            $user = User::with(['roles', 'dosen', 'mahasiswa'])->findOrFail($id);

            // Dapatkan peran pengguna
            $user->role_name = Str::ucfirst($user->roles->pluck('name')->implode(', '));

            // Buat array data sesuai dengan peran pengguna
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'role_name' => $user->role_name,
                'foto' => $user->foto,
            ];

            // Tambahkan data tambahan berdasarkan peran pengguna
            switch ($user->role_name) {
                case "Kajur":
                case "Kaprodi":
                    $userData['nip'] = $user->dosen->nip;
                    // Tidak perlu break karena logika serupa untuk Kajur dan Kaprodi
                case "Admin":
                    $userData['email'] = $user->email;
                    break;
                case "Dosen":
                    $userData['nip'] = $user->dosen->nip;
                    break;
                case "Mahasiswa":
                    $userData['nim'] = $user->mahasiswa->nim;
                    break;
                default:
                    return redirect()->back()->with('error', 'Peran pengguna tidak ditemukan.');
            }

            // Tampilkan halaman dengan data yang telah dipersiapkan
            return view('admin.pages.users.reset-password', [
                'icon' => 'edit',
                'title' => 'Reset Password',
                'subtitle' => 'Reset Password Pengguna',
                'active' => 'users',
                'user' => $userData,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data pengguna tidak ditemukan.');
        }
    }

    public function resetPassword(Request $request, $id)
    {
        // Validasi data yang dikirim
        $request->validate([
            'password_baru' => 'required|min:6',
            'konfirmasi_password_baru' => 'same:password_baru',
        ], [
            'password_baru.required' => 'Password baru harus diisi.',
            'password_baru.min' => 'Password baru minimal 6 karakter.',
            'konfirmasi_password_baru.same' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        try {
            // Cari data user berdasarkan id
            $user = User::findOrFail($id);

            // Ubah password
            $user->update([
                'password' => bcrypt($request->password_baru),
            ]);

            // Mengalihkan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Password berhasil diatur ulang.');
        } catch (\Exception $e) {
            // Mengalihkan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan! gagal mereset password. Silahkan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            // Cari data user berdasarkan id
            $user = User::findOrFail($id);

            // Hapus peran pengguna
            $user->roles()->detach();

            // Hapus data berdasarkan peran
            if ($user->hasRole('Mahasiswa')) {
                $user->mahasiswa()->delete();
            } elseif ($user->hasRole('Kajur') || $user->hasRole('Kaprodi') || $user->hasRole('Dosen')) {
                $user->dosen->pendidikan()->delete();
                $user->dosen->bidangKepakaran()->detach();
                $user->dosen()->delete();
            }

            // Hapus foto dari storage/penyimpanan
            if ($user->foto && Storage::exists('usersProfile/' . $user->foto)) {
                Storage::delete('usersProfile/' . $user->foto);
            }

            // Hapus data pengguna
            $user->delete();

            // Redirect ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            // Redirect ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan! data gagal dihapus.');
        }
    }

    public function byAdmin(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'Admin');
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
        // Validasi data yang dikirim
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
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

        try {
            // Jika ada file yang dikirim
            if ($request->hasFile('foto')) {
                // Namakan file
                $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

                // Simpan file ke storage/penyimpanan
                $request->file('foto')->storeAs('usersProfile', $nameFile);
            }

            // Simpan data user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'foto' => $nameFile ?? null // Jika tidak ada file, nilai foto adalah null
            ]);

            // Tetapkan peran sebagai Admin
            $user->assignRole('Admin');

            // Redirect ke halaman users -> admin -> index dengan pesan sukses
            return redirect()->route('users.byAdmin')->with('success', 'Data admin berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Redirect ke halaman users -> admin -> index dengan pesan error
            return redirect()->route('users.byAdmin')->with('error', 'Terjadi kesalahan! data admin gagal ditambahkan.');
        }
    }

    public function editAdmin($id)
    {
        try {
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
        } catch (\Exception $e) {
            // Redirect ke halaman users -> admin -> index dengan pesan error
            return redirect()->route('users.byAdmin')->with('error', 'Terjadi kesalahan! data admin tidak ditemukan.');
        }
    }

    public function updateAdmin(Request $request, $id)
    {
        // Validasi data yang dikirim
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

        try {
            // Cari data user berdasarkan id
            $user = User::findOrFail($id);

            // Persiapkan data yang akan diupdate
            $dataUser = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Jika ada file foto yang dikirim
            if ($request->hasFile('foto')) {
                // Hapus file foto lama jika ada
                if ($user->foto && Storage::exists('usersProfile/' . $user->foto)) {
                    Storage::delete('usersProfile/' . $user->foto);
                }

                // Namakan file baru
                $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

                // Simpan file ke storage
                $request->file('foto')->storeAs('usersProfile', $nameFile);

                // Tambahkan nama file baru ke data yang akan diupdate
                $dataUser['foto'] = $nameFile;
            }

            // Update data user
            $user->update($dataUser);

            // Redirect ke halaman users -> admin -> index dengan pesan sukses
            return redirect()->route('users.byAdmin')->with('success', 'Data admin berhasil diperbarui.');
        } catch (\Exception $e) {
            // Redirect ke halaman users -> admin -> index dengan pesan error
            return redirect()->route('users.byAdmin')->with('error', 'Terjadi kesalahan! data admin gagal diperbarui.');
        }
    }

    public function byDosen(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // Start with the base query
            $query = User::with('dosen')->whereHas('roles', function ($q) {
                $q->where('name', 'Dosen')
                ->orWhere('name', 'Kajur')
                ->orWhere('name', 'Kaprodi');
            });

            // Apply program_studi filter if provided
            if ($request->has('program_studi') && !empty($request->program_studi)) {
                $query->whereHas('dosen', function ($q) use ($request) {
                    $q->where('program_studi', $request->program_studi);
                });
            }

            // Get the data
            $users = $query->orderBy("name", "asc")->get();

            // Transform data into an array, including role names
            $users = $users->transform(function ($item) {
                $item->role_names = Str::ucfirst($item->roles->pluck('name')->implode(', '));
                return $item;
            })->all();

            // Display data in DataTables format
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
        try {
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
        } catch (\Exception $e) {
            // Redirect ke halaman users -> dosen -> index dengan pesan error
            return redirect()->route('users.byDosen')->with('error', 'Terjadi kesalahan! halaman gagal ditampilkan.');
        }
    }

    public function storeDosen(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'name' => 'required|min:3|unique:users,name',
            'jafa' => 'required',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'umur' => 'required|numeric',
            'nip' => 'required|numeric|unique:dosens,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'program_studi' => 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'name.unique' => 'Nama sudah terdaftar.',
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
            'program_studi.required' => 'Program studi harus dipilih.',
            'program_studi.in' => 'Program studi tidak valid.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa jpeg, png, jpg.',
            'foto.max' => 'Foto maksimal 2MB.',
        ]);

        try {
            // tampung data ke variabel dataUser
            $dataUser = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ];

            // jika ada file yang dikirim
            if ($request->hasFile('foto')) {
                // namakan file
                $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

                // simpan file ke storage/penyimpanan
                $request->file('foto')->storeAs('usersProfile', $nameFile);

                // simpan data ke variabel dataUser
                $dataUser['foto'] = $nameFile;
            }

            // simpan data
            $user = User::create($dataUser);

            // tetapkan peran
            $user->assignRole('Dosen');

            // tampung data ke variabel dataDosen
            $dataDosen = [
                'nip' => $request->nip,
                'program_studi' => $request->program_studi,
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
            if ($request->pendidikan && count($request->pendidikan) > 0) {
                // simpan data ke tabel pendidikan
                $pendidikanData = [];

                foreach ($request->pendidikan as $pendidikanItem) {
                    // Periksa apakah nilai pendidikan tidak kosong
                    if ($pendidikanItem !== null && $pendidikanItem !== '') {
                        $pendidikanData[] = ['pendidikan' => $pendidikanItem];
                    }
                }

                if (!empty($pendidikanData)) {
                    $dosen->pendidikans()->createMany($pendidikanData);
                }
            }

            // jika ada bidang kepakaran
            if ($request->bidang_kepakaran && count($request->bidang_kepakaran) > 0) {
                // simpan data ke tabel bidang kepakaran
                $dosen->bidangKepakarans()->attach($request->bidang_kepakaran);
            }

            // mengalihkan ke halaman users -> dosen -> index
            return redirect()->route('users.byDosen')->with('success', 'Data dosen berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Redirect ke halaman users -> dosen -> index dengan pesan error
            return redirect()->route('users.byDosen')->with('error', 'Terjadi kesalahan! data dosen gagal ditambahkan.');
        }
    }

    public function editDosen($id)
    {
        try {
            // cari data berdasarkan peran (dosen) dan id
            $user = User::with('dosen')->whereHas('roles', function ($q) {
                $q->where('name', 'Kajur')
                ->orWhere('name', 'Kaprodi')
                ->orWhere('name', 'Dosen');
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
        } catch (\Exception $e) {
            // Redirect ke halaman users -> dosen -> index dengan pesan error
            return redirect()->route('users.byDosen')->with('error', 'Terjadi kesalahan! halaman gagal ditampilkan.');
        }
    }

    public function updateDosen(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'name' => 'required|min:3|unique:users,name,' . ($id ?? ''),
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'umur' => 'required|numeric',
            'jafa' => 'required',
            'nip' => 'required|numeric|unique:dosens,nip,' . ($id ? $id . ',user_id' : ''),
            'email' => 'required|email|unique:users,email,' . ($id ?? ''),
            'program_studi' => 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'name.unique' => 'Nama sudah terdaftar.',
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
            'program_studi.required' => 'Program studi harus dipilih.',
            'program_studi.in' => 'Program studi tidak valid.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa jpeg, png, jpg.',
            'foto.max' => 'Foto maksimal 2MB.',
        ]);

        try {
            $user = User::with('dosen')->findOrFail($id);

            // jika ada file yang dikirim
            if ($request->hasFile('foto')) {
                // cek apakah ada file yang lama
                if (Storage::exists('usersProfile/' . $user->foto)) {
                    // hapus file
                    Storage::delete('usersProfile/' . $user->foto);
                }

                // namakan file
                $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

                // simpan file ke storage/penyimpanan
                $request->file('foto')->storeAs('usersProfile', $nameFile);

                // Tampung data ke variabel
                $dataUser = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'foto' => $nameFile,
                ];
            } else {
                $dataUser = [
                    'name' => $request->name,
                    'email' => $request->email,
                ];
            }

            // update data user
            $user->update($dataUser);

            $dataDosen = [
                'nip' => $request->nip,
                'program_studi ' => $request->program_studi,
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
            return redirect()->route('users.byDosen')->with('success', 'Data dosen berhasil diperbarui.');
        } catch (\Exception $e) {
            // Redirect ke halaman users -> dosen -> index dengan pesan error
            return redirect()->route('users.byDosen')->with('error', 'Terjadi kesalahan! data dosen gagal diperbarui.');
        }
    }

    public function byMahasiswa(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // Start with the base query
            $query = User::with('mahasiswa')->whereHas('roles', function ($q) {
                $q->where('name', 'mahasiswa');
            });

            // Apply program_studi filter if provided
            if ($request->has('program_studi') && !empty($request->program_studi)) {
                $query->whereHas('mahasiswa', function ($q) use ($request) {
                    $q->where('program_studi', $request->program_studi);
                });
            }

            // Get the data
            $users = $query->get();

            // Transform data into an array
            $users = $users->transform(function ($item) {
                return $item;
            })->all();

            // Display data in DataTables format
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
        // Validasi data yang dikirim
        $request->validate([
            'name' => 'required|min:3',
            'nim' => 'required|numeric|unique:mahasiswas,nim',
            'program_studi' => 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI',
            'angkatan' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'nim.required' => 'NIM harus diisi.',
            'nim.numeric' => 'NIM harus berupa angka.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'program_studi.required' => 'Program Studi harus diisi.',
            'program_studi.in' => 'Program Studi tidak valid.',
            'angkatan.required' => 'Angkatan harus diisi.',
            'angkatan.numeric' => 'Angkatan harus berupa angka.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa jpeg, png, jpg.',
            'foto.max' => 'Foto maksimal 2MB.',
        ]);

        try {
            // Jika ada file yang dikirim
            if ($request->hasFile('foto')) {
                // Namakan file
                $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();

                // Simpan file ke storage/penyimpanan
                $request->file('foto')->storeAs('usersProfile', $nameFile);
            }

            // Simpan data user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'foto' => $request->hasFile('foto') ? $nameFile : null,
            ]);

            // Tetapkan peran
            $user->assignRole('Mahasiswa');

            // Simpan data ke tabel mahasiswa
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'program_studi' => $request->program_studi,
                'angkatan' => $request->angkatan,
            ]);

            // Mengalihkan ke halaman users -> mahasiswa -> index dengan pesan sukses
            return redirect()->route('users.byMahasiswa')->with('success', 'Data mahasiswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Redirect ke halaman users -> mahasiswa -> index dengan pesan error
            return redirect()->route('users.byMahasiswa')->with('error', 'Terjadi kesalahan! Data mahasiswa gagal ditambahkan.');
        }
    }

    public function editMahasiswa($id)
    {
        try {
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
        } catch (\Exception $e) {
            // Redirect ke halaman users -> mahasiswa -> index dengan pesan error
            return redirect()->route('users.byMahasiswa')->with('error', 'Terjadi kesalahan! halaman gagal ditampilkan.');
        }
    }

    public function updateMahasiswa(Request $request, $id)
    {
        // Validasi data yang dikirim
        $request->validate([
            'name' => 'required|min:3',
            'nim' => 'required|numeric|unique:mahasiswas,nim,' . ($id ? $id . ',user_id' : ''),
            'program_studi' => 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI',
            'angkatan' => 'required|numeric',
            'email' => 'required|email|unique:users,email,' . ($id ?? ''),
            'foto' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Minimal 3 karakter.',
            'nim.required' => 'NIM harus diisi.',
            'nim.numeric' => 'NIM harus berupa angka.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'program_studi.required' => 'Program Studi harus diisi.',
            'program_studi.in' => 'Program Studi tidak valid.',
            'angkatan.required' => 'Angkatan harus diisi.',
            'angkatan.numeric' => 'Angkatan harus berupa angka.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa jpeg, png, jpg.',
            'foto.max' => 'Foto maksimal 2MB.',
        ]);
    
        try {
            // Cari data berdasarkan id
            $user = User::with('mahasiswa')->findOrFail($id);
    
            // Inisialisasi variabel dataUser
            $dataUser = [
                'name' => $request->name,
                'email' => $request->email,
            ];
    
            // Jika ada file yang dikirim
            if ($request->hasFile('foto')) {
                // Cek apakah ada file yang lama
                if ($user->foto && Storage::exists('usersProfile/' . $user->foto)) {
                    // Hapus file lama
                    Storage::delete('usersProfile/' . $user->foto);
                }
    
                // Namakan file baru
                $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('foto')->extension();
    
                // Simpan file ke storage/penyimpanan
                $request->file('foto')->storeAs('usersProfile', $nameFile);
    
                // Tambahkan nama file baru ke dataUser
                $dataUser['foto'] = $nameFile;
            }
    
            // Update data user
            $user->update($dataUser);
    
            // Update data mahasiswa
            $user->mahasiswa()->update([
                'nim' => $request->nim,
                'program_studi' => $request->program_studi,
                'angkatan' => $request->angkatan,
            ]);
    
            // Mengalihkan ke halaman users -> mahasiswa -> index dengan pesan sukses
            return redirect()->route('users.byMahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui.');
        } catch (\Exception $e) {
            // Redirect ke halaman users -> mahasiswa -> index dengan pesan error
            return redirect()->route('users.byMahasiswa')->with('error', 'Terjadi kesalahan! Data mahasiswa gagal diperbarui. ' . $e->getMessage());
        }
    }    

    public function importMahasiswa(Request $request)
    {
        // \Log::info('ImportMahasiswa function called');
        
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ], [
            'file.required' => 'File harus diisi.',
            'file.mimes' => 'File harus berupa csv.',
        ]);
    
        if ($validator->fails()) {
            // \Log::error('Validation failed', ['errors' => $validator->errors()->all()]);
            return response()->json(['error' => $validator->errors()->all()]);
        }
    
        // Read the CSV file
        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $handle = fopen($filePath, 'r');
    
        if ($handle === false) {
            Log::error('File could not be opened', ['file_path' => $filePath]);
            return response()->json(['error' => 'File tidak bisa dibaca.']);
        }
    
        // Skip the header row
        fgetcsv($handle, 0, ';');
    
        $chunksize = 100;
        $rowsImported = 0;
        while (!feof($handle)) {
            $chunkdata = [];
    
            for ($i = 0; $i < $chunksize; $i++) {
                $data = fgetcsv($handle, 0, ';');
                if ($data === false) {
                    break;
                }
                $chunkdata[] = $data;
            }
    
            $rowsImported += $this->processChunkData($chunkdata);
        }
        fclose($handle);
    
        return response()->json(['success' => "Data Mahasiswa berhasil diimport. Total baris: $rowsImported"]);
    }
    
    public function processChunkData($chunkdata)
    {
        $rowsImported = 0;
        foreach ($chunkdata as $column) {
            // Skip the row if it looks like the header
            if ($column[0] === 'NO' && $column[1] === 'ANGKATAN' && $column[2] === 'NIM') {
                // \Log::info('Header row skipped', ['column' => $column]);
                continue;
            }
    
            if (count($column) < 7) { // Ensure the column count matches expected
                Log::warning('Insufficient columns', ['column' => $column]);
                continue; // Skip rows with insufficient columns
            }
    
            $angkatan = $column[1];
            $nim = $column[2];
            $namaMahasiswa = $column[3];
            $programStudi = $column[6];
    
            try {
                // Check if user already exists
                $existingUser = Mahasiswa::where('nim', $nim)->first();
    
                if ($existingUser) {
                    // \Log::info('Mahasiswa sudah ada', ['user_id' => $existingUser->user_id, 'nim' => $nim]);
                    continue;
                }
    
                // Create new user
                $user = new User();
                $user->name = $namaMahasiswa;
                $user->password = bcrypt($nim);
                $user->save();

                // tetapkan peran
                $user->assignRole('mahasiswa');
    
                // \Log::info('User ditambahkan', ['id' => $user->id, 'name' => $namaMahasiswa]);
    
                // Create new mahasiswa
                $mahasiswa = new Mahasiswa();
                $mahasiswa->user_id = $user->id;
                $mahasiswa->nim = $nim;
                $mahasiswa->program_studi = $programStudi;
                $mahasiswa->angkatan = $angkatan;
                $mahasiswa->save();
    
                // \Log::info('Mahasiswa ditambahkan', ['mahasiswa_id' => $mahasiswa->id, 'user_id' => $user->id]);
    
                $rowsImported++;
            } catch (\Exception $e) {
                Log::error('Error importing row', ['error' => $e->getMessage(), 'column' => $column]);
            }
        }
        return $rowsImported;
    }

    public function changeRole(Request $request, $id)
    {
        // Cari data user berdasarkan id
        $user = User::findOrFail($id);

        // Ambil request role
        $role = $request->input('role');

        if ($role === 'Kajur') {
            // Cari user yang sudah memiliki role 'Kajur'
            $existingKajur = User::role('Kajur')->first();

            // Jika ada user dengan role 'Kajur', ubah role-nya menjadi 'dosen'
            if ($existingKajur) {
                $existingKajur->syncRoles(['Dosen']);
            }

            // Set role user saat ini menjadi 'Kajur'
            $user->syncRoles(['Kajur']);
        } else if ($role === 'Kaprodi') {
            // Ambil program studi user saat ini
            $programStudi = $user->dosen->program_studi ?? null;

            if (!$programStudi) {
                return response()->json(['error' => 'User ini tidak memiliki program studi.'], 400);
            }

            // Cari user yang sudah memiliki role 'Kaprodi' dengan program studi yang sama
            $existingKaprodi = User::role('Kaprodi')->whereHas('dosen', function ($query) use ($programStudi) {
                $query->where('program_studi', $programStudi);
            })->first();

            // Jika ada user dengan role 'Kaprodi' dengan program studi yang sama, ubah role-nya menjadi 'dosen'
            if ($existingKaprodi) {
                $existingKaprodi->syncRoles(['dosen']);
            }

            // Hitung jumlah user dengan role 'Kaprodi' dengan program studi yang berbeda
            $kaprodiCount = User::role('Kaprodi')->whereHas('dosen', function ($query) use ($programStudi) {
                $query->where('program_studi', '!=', $programStudi);
            })->count();

            // Jika ada kurang dari dua user dengan role 'Kaprodi' dari program studi yang berbeda
            if ($kaprodiCount < 2) {
                // Set role user saat ini menjadi 'Kaprodi'
                $user->syncRoles(['Kaprodi']);
            } else {
                // Tambahkan pesan error jika tidak bisa mengganti role ke 'Kaprodi'
                return response()->json(['error' => 'Hanya bisa ada dua Kaprodi dengan program studi yang berbeda.'], 400);
            }
        } else if ($role === 'Dosen') {
            // Set role user saat ini menjadi 'Dosen'
            $user->syncRoles(['Dosen']);
        }

        return response()->json(['success' => 'Role berhasil diubah.'], 200);
    }
}
