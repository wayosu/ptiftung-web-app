<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranMahasiswaBaru;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PendaftaranMahasiswaBaruController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $pendaftaranMahasiswaBarus = PendaftaranMahasiswaBaru::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $pendaftaranMahasiswaBarus = $pendaftaranMahasiswaBarus->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($pendaftaranMahasiswaBarus)
                ->addColumn('aksi', function ($pendaftaranMahasiswaBarus) {
                    return view('admin.pages.mahasiswa-dan-alumni.pendaftaran-mahasiswa-baru.tombol-aksi', compact('pendaftaranMahasiswaBarus'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.pendaftaran-mahasiswa-baru.index', [
            'icon' => 'fas fa-users-rays',
            'title' => 'Pendaftaran Mahasiswa Baru',
            'subtitle' => 'Daftar Informasi Pendaftaran Mahasiswa Baru',
            'active' => 'pendaftaran-mahasiswa-baru',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.pendaftaran-mahasiswa-baru.form', [
            'icon' => 'plus',
            'title' => 'Pendaftaran Mahasiswa Baru',
            'subtitle' => 'Tambah Data Informasi Pendaftaran Mahasiswa Baru',
            'active' => 'pendaftaran-mahasiswa-baru',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'singkatan' => 'required',
            'kepanjangan' => 'required',
            'deskripsi' => 'required',
        ], [
            'singkatan.required' => 'Singkatan harus diisi.',
            'kepanjangan.required' => 'Kepanjangan harus diisi.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
        ]);

        try { // jika sukses menambahkan data

            // simpan data
            PendaftaranMahasiswaBaru::create([
                'singkatan' => $request->singkatan,
                'kepanjangan' => $request->kepanjangan,
                'deskripsi' => $request->deskripsi,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('pendaftaranMahasiswaBaru.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('pendaftaranMahasiswaBaru.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model PendaftaranMahasiswaBaru berdasarkan id
            $pendaftaranMahasiswaBaru = PendaftaranMahasiswaBaru::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.mahasiswa-dan-alumni.pendaftaran-mahasiswa-baru.form', [
                'icon' => 'edit',
                'title' => 'Kerja Sama Luar Negeri',
                'subtitle' => 'Edit Kerja Sama Luar Negeri',
                'active' => 'kerja-sama-luar-negeri',
                'pendaftaranMahasiswaBaru' => $pendaftaranMahasiswaBaru,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('pendaftaranMahasiswaBaru.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('pendaftaranMahasiswaBaru.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'singkatan' => 'required',
            'kepanjangan' => 'required',
            'deskripsi' => 'required',
        ], [
            'singkatan.required' => 'Singkatan harus diisi.',
            'kepanjangan.required' => 'Kepanjangan harus diisi.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
        ]);

        try { // jika id ditemukan
            // ambil data dari model PendaftaranMahasiswaBaru berdasarkan id
            $pendaftaranMahasiswaBaru = PendaftaranMahasiswaBaru::findOrFail($id);

            $pendaftaranMahasiswaBaru->update([
                'singkatan' => $request->singkatan,
                'kepanjangan' => $request->kepanjangan,
                'deskripsi' => $request->deskripsi,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('pendaftaranMahasiswaBaru.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('pendaftaranMahasiswaBaru.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('pendaftaranMahasiswaBaru.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model PendaftaranMahasiswaBaru berdasarkan id
            $pendaftaranMahasiswaBaru = PendaftaranMahasiswaBaru::findOrFail($id);

            // hapus data dari table pendaftaran_mahasiswa_barus
            $pendaftaranMahasiswaBaru->delete();

            return redirect()->route('pendaftaranMahasiswaBaru.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('pendaftaranMahasiswaBaru.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('pendaftaranMahasiswaBaru.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
