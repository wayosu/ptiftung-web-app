<?php

namespace App\Http\Controllers;

use App\Models\ProfilProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengaturanController extends Controller
{
    public function pengaturanAkun()
    {
        return view('admin.pages.pengaturan.pengaturan-akun', [
            'icon' => 'fa-solid fa-user-gear',
            'title' => 'Pengaturan Akun',
            'active' => 'pengaturan-akun',
        ]);
    }

    public function updateInformasiAkun(Request $request)
    {
        if (auth()->user()->hasRole('admin')) {
            $request->only(['name', 'email']);

            $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            ], [
                'name.required' => 'Nama Lengkap wajib diisi.',
                'name.min' => 'Nama Lengkap minimal 3 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
            ]);

            try {
                auth()->user()->update([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                ]);
                return redirect()->route('pengaturanAkun')->with('success', 'Informasi akun telah diperbarui.');
            } catch (\Exception $e) {
                return redirect()->route('pengaturanAkun')->with('error', 'Gagal memperbarui informasi akun!');
            }
        } elseif (auth()->user()->hasRole('dosen')) {
            $request->only(['name', 'nip']);

            $request->validate([
                'name' => 'required|min:3',
                'nip' => 'required|unique:users,nip,' . auth()->user()->id,
            ], [
                'name.required' => 'Nama Lengkap wajib diisi.',
                'name.min' => 'Nama Lengkap minimal 3 karakter.',
                'nip.required' => 'NIP wajib diisi.',
                'nip.unique' => 'NIP sudah terdaftar.',
            ]);

            try {
                auth()->user()->update([
                    'name' => $request->input('name'),
                    'nip' => $request->input('nip'),
                ]);
                return redirect()->route('pengaturanAkun')->with('success', 'Informasi akun telah diperbarui.');
            } catch (\Exception $e) {
                return redirect()->route('pengaturanAkun')->with('error', 'Gagal memperbarui informasi akun!');
            }
        } elseif (auth()->user()->hasRole('mahasiswa')) {
        } else {
            return redirect()->route('pengaturanAkun')->with('error', 'Gagal memperbarui informasi akun. Peran pengguna tidak ditemukan!');
        }
    }

    public function updatePasswordAkun(Request $request)
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

        try {
            auth()->user()->update([
                'password' => bcrypt($request->password_baru),
            ]);

            return redirect()->back()->with('success', 'Password berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui password!');
        }
    }

    public function pengaturanSistem()
    {
        // ambil data (hanya nama_program_studi, nama_dasbor, logo, link_embed_video_profil)
        $profil = ProfilProgramStudi::where('id', 1)->first([
            'nama_program_studi',
            'nama_dasbor',
            'logo',
            'link_embed_video_profil',
        ]);

        // tampilkan halaman
        return view('admin.pages.pengaturan.pengaturan-sistem', [
            'icon' => 'fa-solid fa-gear',
            'title' => 'Pengaturan Sistem',
            'active' => 'pengaturan-sistem',
            'namaProgramStudi' => $profil->nama_program_studi,
            'namaDasbor' => $profil->nama_dasbor,
            'logo' => $profil->logo,
            'linkEmbedVideoProfil' => $profil->link_embed_video_profil,
        ]);
    }

    public function updatePengaturanSistem(Request $request)
    {
        $request->only(['nama_program_studi', 'nama_dasbor']);

        $request->validate([
            'nama_program_studi' => 'min:3',
            'nama_dasbor' => 'min:3',
        ], [
            'nama_program_studi.min' => 'Minimal 3 karakter.',
            'nama_dasbor.min' => 'Minimal 3 karakter.',
        ]);

        try {
            $profil = ProfilProgramStudi::findOrFail(1);

            $profil->update([
                'nama_program_studi' => $request->input('nama_program_studi'),
                'nama_dasbor' => $request->input('nama_dasbor'),
            ]);

            return redirect()->route('pengaturanSistem')->with('success', 'Pengaturan sistem telah diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('pengaturanSistem')->with('error', 'Gagal memperbarui pengaturan sistem. Data tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->route('pengaturanSistem')->with('error', 'Gagal memperbarui pengaturan sistem!');
        }
    }

    public function resetToFactory()
    {
        try {
            // Clear the contents of the storage directory
            $clearStorageCommand = 'rm -rf ' . storage_path('app/*');
            shell_exec($clearStorageCommand);

            // Run your other commands if needed
            Artisan::call('refresh-database');

            return redirect()->route('pengaturanSistem')->with('success', 'Setel ke Pengaturan Pabrik berhasil.');
        } catch (\Exception $e) {
            return redirect()->route('pengaturanSistem')->with('error', 'Gagal memperbarui pengaturan sistem!');
        }
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'image|mimes:png|max:2048',
        ], [
            'logo.image' => 'File harus berupa gambar.',
            'logo.mimes' => 'File harus berupa PNG.',
            'logo.max' => 'File tidak boleh lebih dari 2 MB.',
        ]);

        try {
            $profil = ProfilProgramStudi::findOrFail(1);

            if ($request->hasFile('logo')) {
                // cek apakah ada file yang lama
                if (Storage::exists('public/profilProgramStudi/' . $profil->logo)) {
                    // hapus file
                    Storage::delete('public/profilProgramStudi/' . $profil->logo);
                }

                // namakan file
                $nameFile = md5(time() . Str::random(5)) . '.' . $request->file('logo')->extension();

                // simpan file ke storage/penyimpanan
                $request->file('logo')->storeAs('public/profilProgramStudi', $nameFile);

                // simpan data
                $profil->update([
                    'logo' => $nameFile
                ]);

                return redirect()->route('pengaturanSistem')->with('success', 'Logo Program Studi berhasil diperbarui.');
            } else {
                return redirect()->route('pengaturanSistem')->with('error', 'Gagal memperbarui Logo. File logo tidak boleh kosong!');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('pengaturanSistem')->with('error', 'Gagal memperbarui Logo. Data tidak ditemukan.');
        } catch (\Exception $e) { // jika gagal mengupdate data
            return redirect()->route('pengaturanSistem')->with('error', 'Gagal memperbarui Logo!');
        }
    }
}
