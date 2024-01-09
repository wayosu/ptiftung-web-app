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
        // tampilkan view
        return view('admin.pages.users.index', [
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
        // validasi data
        $validasi = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'role' => 'required',
            'password' => 'required|min:6|max:50',
        ], [
            'name.required' => 'Nama harus diisi!',
            'name.min' => 'Nama minimal 3 karakter!',
            'name.max' => 'Nama maksimal 50 karakter!',
            'role.required' => 'Role harus dipilih!',
            'password.required' => 'Password harus diisi!',
            'password.min' => 'Password minimal 6 karakter!',
            'password.max' => 'Password maksimal 50 karakter!',
        ]);

        // cek role yang dipilih dan validasi berdasarkan role
        if ($request->role == 'admin') {
            $validasi = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
            ], [
                'email.required' => 'Email harus diisi!',
                'email.email' => 'Email tidak valid!',
                'email.unique' => 'Email sudah terdaftar!',
            ]);
        }

        if ($request->role == 'dosen') {
            $validasi = Validator::make($request->all(), [
                'nip' => 'required|unique:users',
            ], [
                'nip.required' => 'NIP harus diisi!',
                'nip.unique' => 'NIP sudah terdaftar!',
            ]);
        }

        if ($request->role == 'mahasiswa') {
            $validasi = Validator::make($request->all(), [
                'nim' => 'required|unique:users',
            ], [
                'nim.required' => 'NIM harus diisi!',
                'nim.unique' => 'NIM sudah terdaftar!',
            ]);
        }

        // cek validasi
        if ($validasi->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'errors' => $validasi->errors()->all(),
                'message' => 'Data gagal disimpan!',
            ]);
        } else {
            // simpan data ke database
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'nim' => $request->nim,
                'password' => bcrypt($request->password),
            ];
            $user = User::create($data);

            // cek role yang dipilih dan simpan ke database berdasarkan role yang dipilih
            // jika role dosen, tambahkan data dosen 
            // jika role mahasiswa, tambahkan data mahasiswa
            if ($request->role == 'dosen') {
                Dosen::create([
                    'user_id' => $user->id,
                ]);
            } else if ($request->role == 'mahasiswa') {
                Mahasiswa::create([
                    'user_id' => $user->id,
                ]);
            }

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
        // validasi data
        $validasi = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'role' => 'required',
        ], [
            'name.required' => 'Nama harus diisi!',
            'name.min' => 'Nama minimal 3 karakter!',
            'name.max' => 'Nama maksimal 50 karakter!',
            'role.required' => 'Role harus dipilih!',
        ]);

        // cek role yang dipilih dan validasi berdasarkan role
        if ($request->role == 'admin') {
            $validasi = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email,' . $id,
            ], [
                'email.required' => 'Email harus diisi!',
                'email.email' => 'Email tidak valid!',
                'email.unique' => 'Email sudah terdaftar!',
            ]);
        }

        if ($request->role == 'dosen') {
            $validasi = Validator::make($request->all(), [
                'nip' => 'required|unique:users,nip,' . $id,
            ], [
                'nip.required' => 'NIP harus diisi!',
                'nip.unique' => 'NIP sudah terdaftar!',
            ]);
        }

        if ($request->role == 'mahasiswa') {
            $validasi = Validator::make($request->all(), [
                'nim' => 'required|unique:users,nim,' . $id,
            ], [
                'nim.required' => 'NIM harus diisi!',
                'nim.unique' => 'NIM sudah terdaftar!',
            ]);
        }

        // cek apakah password diisi dan validasi password
        if ($request->password) {
            $validasi = Validator::make($request->all(), [
                'password' => 'min:6|max:50',
            ], [
                'password.min' => 'Password minimal 6 karakter!',
                'password.max' => 'Password maksimal 50 karakter!',
            ]);
        }

        // cek validasi
        if ($validasi->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'errors' => $validasi->errors()->all(),
                'message' => 'Data gagal disimpan!',
            ]);
        } else {
            // simpan data ke database
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
        return view('admin.pages.users.byRole', [
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
}
