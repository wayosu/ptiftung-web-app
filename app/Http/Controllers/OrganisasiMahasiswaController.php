<?php

namespace App\Http\Controllers;

use App\Models\OrganisasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class OrganisasiMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $organisasiMahasiswas = OrganisasiMahasiswa::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $organisasiMahasiswas = $organisasiMahasiswas->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($organisasiMahasiswas)
                ->addColumn('aksi', function ($organisasiMahasiswas) {
                    return view('admin.pages.mahasiswa-dan-alumni.organisasi-mahasiswa.tombol-aksi', compact('organisasiMahasiswas'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.organisasi-mahasiswa.index', [
            'icon' => 'fas fa-users-rays',
            'title' => 'Organisasi Mahasiswa',
            'subtitle' => 'Daftar Informasi Organisasi Mahasiswa',
            'active' => 'organisasi-mahasiswa',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.organisasi-mahasiswa.form', [
            'icon' => 'plus',
            'title' => 'Organisasi Mahasiswa',
            'subtitle' => 'Tambah Informasi Organisasi Mahasiswa',
            'active' => 'organisasi-mahasiswa',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'nama_organisasi' => 'required',
            'tingkat_organisasi' => 'required|in:Universitas,Fakultas',
            'deskripsi' => 'required',
        ], [
            'nama_organisasi.required' => 'Nama Organisasi harus diisi.',
            'tingkat_organisasi.required' => 'Tingkat Organisasi harus dipilih.',
            'tingkat_organisasi.in' => 'Tingkat Organisasi yang dipilih tidak valid.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
        ]);

        try { // jika sukses menambahkan data

            // simpan data
            OrganisasiMahasiswa::create([
                'nama_organisasi' => $request->nama_organisasi,
                'slug' => Str::slug($request->nama_organisasi),
                'tingkat_organisasi' => $request->tingkat_organisasi,
                'deskripsi' => $request->deskripsi,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('organisasiMahasiswa.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('organisasiMahasiswa.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model OrganisasiMahasiswa berdasarkan id
            $organisasiMahasiswa = OrganisasiMahasiswa::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.mahasiswa-dan-alumni.organisasi-mahasiswa.form', [
                'icon' => 'edit',
                'title' => 'Organisasi Mahasiswa',
                'subtitle' => 'Edit Organisasi Mahasiswa',
                'active' => 'organisasi-mahasiswa',
                'organisasiMahasiswa' => $organisasiMahasiswa,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'nama_organisasi' => 'required',
            'tingkat_organisasi' => 'required|in:Universitas,Fakultas',
            'deskripsi' => 'required',
        ], [
            'nama_organisasi.required' => 'Nama Organisasi harus diisi.',
            'tingkat_organisasi.required' => 'Tingkat Organisasi harus dipilih.',
            'tingkat_organisasi.in' => 'Tingkat Organisasi yang dipilih tidak valid.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
        ]);

        try { // jika id ditemukan
            // ambil data dari model OrganisasiMahasiswa berdasarkan id
            $organisasiMahasiswa = OrganisasiMahasiswa::findOrFail($id);

            $organisasiMahasiswa->update([
                'nama_organisasi' => $request->nama_organisasi,
                'slug' => Str::slug($request->nama_organisasi),
                'tingkat_organisasi' => $request->tingkat_organisasi,
                'deskripsi' => $request->deskripsi,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('organisasiMahasiswa.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('organisasiMahasiswa.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('organisasiMahasiswa.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model OrganisasiMahasiswa berdasarkan id
            $organisasiMahasiswa = OrganisasiMahasiswa::findOrFail($id);

            // hapus data dari table prestasi_mahasiswas
            $organisasiMahasiswa->delete();

            return redirect()->route('organisasiMahasiswa.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('organisasiMahasiswa.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('organisasiMahasiswa.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
