<?php

namespace App\Http\Controllers;

use App\Models\KegiatanMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class KegiatanMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            if (auth()->user()->memilikiPeran('Superadmin') || auth()->user()->memilikiPeran('Admin') || auth()->user()->memilikiPeran('Kajur')) {
                // Start with the base query
                $query = KegiatanMahasiswa::with('createdBy');
    
                // Apply program_studi filter if provided
                if ($request->has('program_studi') && !empty($request->program_studi)) {
                    $query->where('program_studi', $request->program_studi);
                }
            } else {
                $query = KegiatanMahasiswa::where('program_studi', auth()->user()->dosen->program_studi)->with('createdBy');
            }

            // Get the data
            $kegiatanMahasiswas = $query->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $kegiatanMahasiswas = $kegiatanMahasiswas->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($kegiatanMahasiswas)
                ->addColumn('aksi', function ($kegiatanMahasiswas) {
                    return view('admin.pages.kegiatan-mahasiswa.tombol-aksi', compact('kegiatanMahasiswas'));
                })
                ->make(true);
        }

        // tampilkan halaman
        if (auth()->user()->memilikiPeran('Superadmin') || auth()->user()->memilikiPeran('Admin') || auth()->user()->memilikiPeran('Kajur')) {
            return view('admin.pages.kegiatan-mahasiswa.index', [
                'icon' => 'fa-solid fa-people-group',
                'title' => 'Kegiatan Mahasiswa',
                'subtitle' => 'Daftar Kegiatan Mahasiswa',
                'active' => 'kegiatan-mahasiswa',
            ]);
        } else {
            return view('admin.pages.kegiatan-mahasiswa.index', [
                'icon' => 'fa-solid fa-people-group',
                'title' => 'Kegiatan Mahasiswa - ' . auth()->user()->dosen->program_studi,
                'subtitle' => 'Daftar Kegiatan Mahasiswa - ' . auth()->user()->dosen->program_studi,
                'active' => 'kegiatan-mahasiswa',
            ]);
        }
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.kegiatan-mahasiswa.form', [
            'icon' => 'plus',
            'title' => 'Kegiatan Mahasiswa',
            'subtitle' => 'Tambah Kegiatan Mahasiswa',
            'active' => 'kegiatan-mahasiswa',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'nama_kegiatan' => 'required',
            'program_studi' => 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI',
            'deskripsi' => 'sometimes|nullable|string',
        ], [
            'nama_kegiatan.required' => 'Nama Kegiatan harus diisi!',
            'program_studi.required' => 'Program Studi harus diisi!',
            'program_studi.in' => 'Program Studi tidak valid!',
            'deskripsi.string' => 'Deskripsi harus berupa teks!',
        ]);

        try { // jika sukses menambahkan data
            // cek jika ada slug yang sama
            $kegiatanMahasiswa = KegiatanMahasiswa::where('slug', Str::slug($request->nama_kegiatan . '-' . $request->program_studi))->first();
            if ($kegiatanMahasiswa) {
                return redirect()->route('kegiatanMahasiswa.index')->with('error', 'Data gagal ditambahkan!. Data yang sama sudah ada!');
            }

            // simpan data
            KegiatanMahasiswa::create([
                'nama_kegiatan' => $request->nama_kegiatan,
                'slug' => Str::slug($request->nama_kegiatan . '-' . $request->program_studi),
                'deskripsi' => $request->deskripsi ?? null,
                'program_studi' => $request->program_studi,
                'created_by' => auth()->user()->id,
            ]);

            // mengalihkan ke halaman bidang kepakaran -> index
            return redirect()->route('kegiatanMahasiswa.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('kegiatanMahasiswa.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika sukses mengambil data
            // cari data berdasarkan id
            $kegiatanMahasiswa = KegiatanMahasiswa::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.kegiatan-mahasiswa.form', [
                'icon' => 'edit',
                'title' => 'Kegiatan Mahasiswa',
                'subtitle' => 'Edit Kegiatan Mahasiswa',
                'active' => 'kegiatan-mahasiswa',
                'kegiatanMahasiswa' => $kegiatanMahasiswa
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kegiatanMahasiswa.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kegiatanMahasiswa.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'nama_kegiatan' => 'required',
            'program_studi' => 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI',
            'deskripsi' => 'sometimes|nullable|string',
        ], [
            'nama_kegiatan.required' => 'Nama Kegiatan harus diisi!',
            'nama_kegiatan.unique' => 'Nama Kegiatan sudah ada!',
            'program_studi.required' => 'Program Studi harus diisi!',
            'program_studi.in' => 'Program Studi tidak valid!',
            'deskripsi.string' => 'Deskripsi harus berupa teks!',
        ]);

        try { // jika sukses update data
            // cari data berdasarkan id
            $kegiatanMahasiswa = KegiatanMahasiswa::findOrFail($id);

            // cek jika ada slug yang sama
            $kegiatanMahasiswaSama = KegiatanMahasiswa::where('slug', Str::slug($request->nama_kegiatan . '-' . $request->program_studi))->first();
            if ($kegiatanMahasiswaSama && $kegiatanMahasiswaSama->id != $kegiatanMahasiswa->id) {
                return redirect()->route('kegiatanMahasiswa.index')->with('error', 'Data gagal diperbarui!. Data yang sama sudah ada!');
            }

            // update data
            $kegiatanMahasiswa->update([
                'nama_kegiatan' => $request->nama_kegiatan,
                'slug' => Str::slug($request->nama_kegiatan . '-' . $request->program_studi),
                'deskripsi' => $request->deskripsi ?? null,
                'program_studi' => $request->program_studi,
                'updated_by' => auth()->user()->id,
            ]);

            // mengalihkan ke halaman bidang kepakaran -> index
            return redirect()->route('kegiatanMahasiswa.index')->with('success', 'Data berhasil diperbarui!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kegiatanMahasiswa.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal update data
            return redirect()->route('kegiatanMahasiswa.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // cari data berdasarkan id
            $kegiatanMahasiswa = KegiatanMahasiswa::findOrFail($id);

            // hapus data
            $kegiatanMahasiswa->delete();

            // mengalihkan ke halaman bidang kepakaran -> index
            return redirect()->route('kegiatanMahasiswa.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kegiatanMahasiswa.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('kegiatanMahasiswa.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
