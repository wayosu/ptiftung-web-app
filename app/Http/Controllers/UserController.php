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
use Illuminate\Support\Arr;

class UserController extends Controller
{
    public function index()
    {
        // tampilkan view
        return view('admin.pages.users', [
            'icon' => 'users',
            'title' => 'All Users',
            'subtitle' => 'Daftar seluruh pengguna aplikasi.',
            'active' => 'users',
        ]);
    }

    public function indexAjax()
    {
        // ambil data
        $data = User::with('roles')->orderBy('created_at', 'desc')->get();

        // transformasi data ke bentuk array
        $data = $data->transform(function ($item) {
            $item->role_names = Str::ucfirst($item->roles->pluck('name')->implode(', '));
            return $item;
        })->all();

        // kembalikan response
        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('admin.components.users.tombolAksi', compact('data'));
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        // Validasi data
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'password' => 'required|min:6|max:50',
            'email' => ($request->role == 'admin') ? 'required|email|unique:users' : '',
            'nip' => ($request->role == 'dosen') ? 'required|unique:users' : '',
            'nim' => ($request->role == 'mahasiswa') ? 'required|unique:users' : '',
        ], [
            'name.required' => 'Nama harus diisi!',
            'role.required' => 'Role harus dipilih!',
            'role.in' => 'Role tidak valid',
            'password.required' => 'Password harus diisi!',
            'password.min' => 'Password minimal 6 karakter!',
            'password.max' => 'Password maksimal 50 karakter!',
            'email.required' => 'Email harus diisi!',
            'email.email' => 'Email tidak valid!',
            'email.unique' => 'Email sudah terdaftar!',
            'nip.required' => 'NIP harus diisi!',
            'nip.unique' => 'NIP sudah terdaftar!',
            'nim.required' => 'NIM harus diisi!',
            'nim.unique' => 'NIM sudah terdaftar!',
        ]);

        // Cek validasi
        if ($validasi->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'errors' => $validasi->errors()->all(),
                'message' => 'Data gagal disimpan!',
            ]);
        } else {
            // Simpan data ke database
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'nim' => $request->nim,
                'password' => bcrypt($request->password),
            ];
            $user = User::create($data);

            // Cek role yang dipilih dan simpan ke database berdasarkan role yang dipilih
            // Jika role dosen, tambahkan data dosen
            // Jika role mahasiswa, tambahkan data mahasiswa
            if ($request->role == 'dosen') {
                Dosen::create([
                    'user_id' => $user->id,
                ]);
            } else if ($request->role == 'mahasiswa') {
                Mahasiswa::create([
                    'user_id' => $user->id,
                ]);
            }

            // Tambahkan role ke user
            $user->assignRole($request->role);

            // Kembalikan response
            return response()->json([
                'status' => 'success',
                'code' => '200',
                'message' => 'Data berhasil disimpan!',
            ]);
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        // ambil data berdasarkan id
        $data = User::with('roles')->findOrFail($id);

        // $data = $data->transform(function ($item) {
        //     $item->role_names = Str::ucfirst($item->roles->pluck('name')->implode(', '));
        //     return $item;
        // })->all();

        // kembalikan response
        return response()->json([
            'status' => 'success',
            'code' => '200',
            'result' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'password' => ($request->password) ? 'min:6|max:50' : '',
            'email' => ($request->role == 'admin') ? 'required|email|unique:users,email,' . $id : '',
            'nip' => ($request->role == 'dosen') ? 'required|unique:users,nip,' . $id : '',
            'nim' => ($request->role == 'mahasiswa') ? 'required|unique:users,nim,' . $id : '',
        ], [
            'name.required' => 'Nama harus diisi!',
            'role.required' => 'Role harus dipilih!',
            'role.in' => 'Role tidak valid',
            'password.min' => 'Password minimal 6 karakter!',
            'password.max' => 'Password maksimal 50 karakter!',
            'email.required' => 'Email harus diisi!',
            'email.email' => 'Email tidak valid!',
            'email.unique' => 'Email sudah terdaftar!',
            'nip.required' => 'NIP harus diisi!',
            'nip.unique' => 'NIP sudah terdaftar!',
            'nim.required' => 'NIM harus diisi!',
            'nim.unique' => 'NIM sudah terdaftar!',
        ]);

        // Cek validasi
        if ($validasi->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'errors' => $validasi->errors()->all(),
                'message' => 'Data gagal disimpan!',
            ]);
        } else {
            // Data yang akan diupdate
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'nim' => $request->nim,
            ];

            // cek apakah password diisi
            if ($request->password) {
                $data['password'] = bcrypt($request->password);
            }

            // update data berdasarkan id
            $user = User::findOrFail($id);
            $user->update($data);

            // hapus role lama
            $user->removeRole($user->roles->pluck('name')->implode(', '));

            // tambahkan role ke user
            $user->assignRole($request->role);

            // kembalikan response
            return response()->json([
                'status' => 'success',
                'code' => '200',
                'message' => 'Data berhasil disimpan!',
            ]);
        }
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

        // kembalikan response
        return response()->json([
            'status' => 'success',
            'code' => '200',
            'message' => 'Data berhasil dihapus!',
        ]);
    }

    public function byRole($role)
    {
        // ubah role menjadi huruf besar pada karakter pertama
        $roleCapitalize = Str::ucfirst($role);

        // cek role
        if ($roleCapitalize !== 'Mahasiswa' && $roleCapitalize !== 'Dosen' && $roleCapitalize !== 'Admin') {
            abort(404);
        }

        // tampilkan view
        return view('admin.pages.users', [
            'icon' => 'users',
            'title' => $roleCapitalize,
            'subtitle' => 'Daftar ' . $roleCapitalize . ' yang terdaftar.',
            'active' => 'users',
            'role' => $role,
        ]);
    }

    public function byRoleAjax($role)
    {
        // ambil data berdasarkan role
        $data = User::whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });

        // cek role untuk memuat data berdasarkan role
        if ($role === 'mahasiswa') {
            // eager loading (with()) untuk memuat data mahasiswa tersebut
            // order berdasarkan nim untuk mahasiswa dengan ascending
            $data->with('mahasiswa')->orderBy('nim', 'asc');
        } else if ($role === 'dosen') {
            // eager loading (with()) untuk memuat data dosen tersebut
            // order berdasarkan name untuk dosen dengan ascending
            $data->with('dosen')->orderBy('name', 'asc');
        } else {
            // order berdasarkan name untuk admin dengan ascending
            $data->orderBy('name', 'asc');
        }

        // ambil data
        $data = $data->get();

        // transformasi data ke bentuk array
        $data = $data->transform(function ($item) {
            $item->role_names = Str::ucfirst($item->roles->pluck('name')->implode(', '));
            return $item;
        })->all();

        // kembalikan data ke DataTable
        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('admin.components.users.tombolAksi', compact('data'));
            })
            ->make(true);
    }

    public function byRoleStore(Request $request, $role)
    {
        if ($role === 'mahasiswa') {
            // Validasi Data
            $validasi = Validator::make($request->all(), [
                'name' => 'required',
                'role' => 'required|in:mahasiswa',
                'nim' => 'required|unique:users',
                'prodi' => 'required',
                'angkatan' => 'required',
                'password' => 'required|min:6|max:50',
            ], [
                'name.required' => 'Nama harus diisi!',
                'role.required' => 'Role harus dipilih!',
                'role.in' => 'Role tidak valid',
                'nim.required' => 'NIM harus diisi!',
                'nim.unique' => 'NIM sudah terdaftar!',
                'prodi.required' => 'Prodi harus diisi!',
                'angkatan.required' => 'Angkatan harus diisi!',
                'password.required' => 'Password harus diisi!',
            ]);

            // Cek validasi
            if ($validasi->fails()) {
                return response()->json([
                    'status' => 'error',
                    'code' => '400',
                    'errors' => $validasi->errors()->all(),
                    'message' => 'Data gagal disimpan!',
                ]);
            } else {
                // Create data user
                $data = User::create([
                    'name' => $request->name,
                    'nim' => $request->nim,
                    'password' => bcrypt($request->password),
                ]);

                // Create data mahasiswa
                Mahasiswa::create([
                    'user_id' => $data->id,
                    'program_studi' => $request->prodi,
                    'angkatan' => $request->angkatan,
                ]);

                // Tambahkan role mahasiswa
                $data->assignRole('mahasiswa');

                // kembalikan response
                return response()->json([
                    'status' => 'success',
                    'code' => '200',
                    'message' => 'Data berhasil disimpan!',
                ]);
            }
        } else if ($role === 'dosen') {
            // $data = Dosen::create($request->all());
        } else {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'message' => 'Data gagal disimpan!',
            ]);
        }
    }

    public function byRoleShow($id, $role)
    {
        if ($role === 'mahasiswa') {
            // ambil data berdasarkan id
            $data = User::with('mahasiswa', 'roles')->select('id', 'name', 'nim')->findOrFail($id);

            // ambil nama role dari $data
            $data->role_names = Str::ucfirst($data->roles->pluck('name')->implode(', '));

            // ambil sebagian data mahasiswa
            $data->prodi = $data->mahasiswa->program_studi;
            $data->angkatan = $data->mahasiswa->angkatan;

            // tabel roles dan mahasiswa tidak perlu di tampilkan
            unset($data->roles);
            unset($data->mahasiswa);

            return response()->json([
                'status' => 'success',
                'code' => '200',
                'result' => $data,
            ]);
        } else if ($role === 'dosen') {
        } else if ($role === 'admin') {
            // ambil beberapa data berdasarkan id
            $data = User::with('roles')->select('id', 'name', 'email')->findOrFail($id);

            // ambil nama role dari $data
            $data->role_names = Str::ucfirst($data->roles->pluck('name')->implode(', '));

            // tabel roles tidak perlu di tampilkan
            unset($data->roles);

            return response()->json([
                'status' => 'success',
                'code' => '200',
                'result' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'message' => 'Data tidak ditemukan!',
            ]);
        }
    }

    public function byRoleEdit($id, $role)
    {
        if ($role === 'mahasiswa') {
            // ambil data berdasarkan id
            $data = User::with('mahasiswa', 'roles')->findOrFail($id);

            // cek apakah data ada
            if ($data === null) {
                return response()->json([
                    'status' => 'error',
                    'code' => '400',
                    'message' => 'Data tidak ditemukan!',
                ]);
            } else {
                // ambil nama role dari $data
                $data->role_names = Str::ucfirst($data->roles->pluck('name')->implode(', '));

                // ambil beberapa field dari $data mahasiswa
                $data->prodi = $data->mahasiswa->program_studi;
                $data->angkatan = $data->mahasiswa->angkatan;

                // tabel roles dan mahasiswa tidak perlu di tampilkan
                unset($data->roles);
                unset($data->mahasiswa);

                return response()->json([
                    'status' => 'success',
                    'code' => '200',
                    'result' => $data,
                ]);
            }
        } else if ($role === 'dosen') {
        } else if ($role === 'admin') {
        } else {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'message' => 'Data tidak ditemukan!',
            ]);
        }
    }

    public function byRoleUpdate(Request $request, $id, $role)
    {
        if ($role === 'mahasiswa') {
            // Penanganan Request only()
            $dataReq = $request->only(['name', 'nim', 'prodi', 'angkatan', 'password']);

            // Validasi data
            $validasi = Validator::make($dataReq, [
                'name' => 'required',
                'nim' => 'required|unique:users,nim,' . $id,
                'prodi' => 'required',
                'angkatan' => 'required',
                'password' => ($dataReq['password']) ? 'min:6|max:50' : '',
            ], [
                'name.required' => 'Nama harus diisi!',
                'nim.required' => 'NIM harus diisi!',
                'nim.unique' => 'NIM sudah terdaftar!',
                'prodi.required' => 'Program Studi harus diisi!',
                'angkatan.required' => 'Angkatan harus diisi!',
                'angkatan.numeric' => 'Angkatan harus berupa angka!',
                'password.min' => 'Password minimal 6 karakter!',
                'password.max' => 'Password maksimal 50 karakter!',
            ]);

            if ($validasi->fails()) {
                return response()->json([
                    'status' => 'error',
                    'code' => '400',
                    'errors' => $validasi->errors()->all(),
                    'message' => 'Data gagal disimpan!',
                ]);
            } else {
                // Cek data user berdasarkan id
                $data = User::with('mahasiswa')->find($id);

                // Cek apakah password diubah
                if (isset($dataReq['password']) && $dataReq['password'] !== '') {
                    $data->password = bcrypt($dataReq['password']);
                }

                // data dari $dataReq
                $data->name = $dataReq['name'];
                $data->nim = $dataReq['nim'];
                $data->mahasiswa->program_studi = $dataReq['prodi'];
                $data->mahasiswa->angkatan = $dataReq['angkatan'];

                // Simpan data
                $data->save();
                $data->mahasiswa->save();

                // kembalikan response
                return response()->json([
                    'status' => 'success',
                    'code' => '200',
                    'message' => 'Data berhasil disimpan!',
                ]);
            }
        } else if ($role === 'dosen') {
        } else if ($role === 'admin') {
            // Penanganan Request only()
            $dataReq = $request->only(['name', 'email', 'password']);

            // Validasi data
            $validasi = Validator::make($dataReq, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => ($dataReq['password']) ? 'min:6|max:50' : '',
            ], [
                'name.required' => 'Nama harus diisi!',
                'email.required' => 'Email harus diisi!',
                'email.email' => 'Email tidak valid!',
                'email.unique' => 'Email sudah terdaftar!',
                'password.min' => 'Password minimal 6 karakter!',
                'password.max' => 'Password maksimal 50 karakter!',
            ]);

            // Cek validasi
            if ($validasi->fails()) {
                return response()->json([
                    'status' => 'error',
                    'code' => '400',
                    'errors' => $validasi->errors()->all(),
                    'message' => 'Data gagal disimpan!',
                ]);
            } else {
                $data = [
                    'name' => $dataReq['name'],
                    'email' => $dataReq['email'],
                ];

                // cek apakah password diisi
                if ($dataReq['password']) {
                    $data['password'] = bcrypt($dataReq['password']);
                }

                // Update data
                $user = User::find($id);
                $user->update($data);

                // kembalikan response
                return response()->json([
                    'status' => 'success',
                    'code' => '200',
                    'message' => 'Data berhasil disimpan!',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'message' => 'Data gagal disimpan!',
            ]);
        }
    }
}
