<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.pages.users.index', [
            'icon' => 'users',
            'title' => 'All Users',
            'subtitle' => 'Daftar seluruh pengguna aplikasi.',
            'active' => 'users',
        ]);
    }

    public function indexAjax()
    {
        $data = User::with('roles')->orderBy('created_at', 'desc')->get();
        $data = $data->transform(function ($item) {
            $item->role_names = Str::ucfirst($item->roles->pluck('name')->implode(', '));
            return $item;
        })->all();

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

            // tambahkan role ke user
            $user->assignRole($request->role);

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
        $data = User::with('roles')->findOrFail($id);

        // $data = $data->transform(function ($item) {
        //     $item->role_names = Str::ucfirst($item->roles->pluck('name')->implode(', '));
        //     return $item;
        // })->all();

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

            $user = User::findOrFail($id);
            $user->update($data);

            // hapus role lama
            $user->removeRole($user->roles->pluck('name')->implode(', '));

            // tambahkan role ke user
            $user->assignRole($request->role);

            return response()->json([
                'status' => 'success',
                'code' => '200',
                'message' => 'Data berhasil disimpan!',
            ]);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->removeRole($user->roles->pluck('name')->implode(', '));
        $user->delete();

        return response()->json([
            'status' => 'success',
            'code' => '200',
            'message' => 'Data berhasil dihapus!',
        ]);
    }

    public function byRole($role)
    {
        $roleCapitalize = Str::ucfirst($role);

        if ($roleCapitalize !== 'Mahasiswa' && $roleCapitalize !== 'Dosen' && $roleCapitalize !== 'Admin') {
            abort(404);
        }

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
        $data = User::whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });

        if ($role === 'mahasiswa') {
            $data->orderBy('nim', 'asc');
        } else if ($role === 'dosen') {
            $data->orderBy('nip', 'asc');
        } else {
            $data->orderBy('name', 'asc');
        }

        $data = $data->get();

        $data = $data->transform(function ($item) {
            $item->role_names = Str::ucfirst($item->roles->pluck('name')->implode(', '));
            return $item;
        })->all();

        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('admin.components.users.tombolAksi', compact('data'));
            })
            ->make(true);
    }
}
