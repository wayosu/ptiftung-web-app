<?php

namespace App\Http\Controllers;

use App\Models\PrestasiMahasiswa;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PrestasiMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $prestasiMahasiswas = PrestasiMahasiswa::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $prestasiMahasiswas = $prestasiMahasiswas->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($prestasiMahasiswas)
                ->addColumn('aksi', function ($prestasiMahasiswas) {
                    return view('admin.pages.mahasiswa-dan-alumni.prestasi-mahasiswa.tombol-aksi', compact('prestasiMahasiswas'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.prestasi-mahasiswa.index', [
            'icon' => 'fas fa-users-rays',
            'title' => 'Prestasi Mahasiswa',
            'subtitle' => 'Daftar Prestasi Mahasiswa',
            'active' => 'prestasi-mahasiswa',
        ]);
    }

    public function create()
    {
        // data tingkat dalam array
        $tingkats = [
            'Internasional' => 'Internasional',
            'Nasional' => 'Nasional',
            'Daerah/Provinsi' => 'Daerah/Provinsi',
            'Wilayah' => 'Wilayah',
            'Regional' => 'Regional',
            'UNG' => 'UNG',
        ];

        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.prestasi-mahasiswa.form', [
            'icon' => 'plus',
            'title' => 'Prestasi Mahasiswa',
            'subtitle' => 'Tambah Prestasi Mahasiswa',
            'active' => 'prestasi-mahasiswa',
            'tingkats' => $tingkats,
        ]);
    }

    public function store(Request $request)
    {
        // data tingkat dalam array
        $tingkats = [
            'Internasional' => 'Internasional',
            'Nasional' => 'Nasional',
            'Daerah/Provinsi' => 'Daerah/Provinsi',
            'Wilayah' => 'Wilayah',
            'Regional' => 'Regional',
            'UNG' => 'UNG',
        ];

        // validasi data yang dikirim
        $request->validate([
            'nama_mahasiswa' => 'required',
            'predikat' => 'required',
            'tingkat' => 'required|in:' . implode(',', array_keys($tingkats)),
            'tahun' => 'required',
            'kegiatan' => 'required',
        ], [
            'nama_mahasiswa.required' => 'Nama Mahasiswa harus diisi.',
            'predikat.required' => 'Predikat harus diisi.',
            'tingkat.required' => 'Tingkat harus dipilih.',
            'tingkat.in' => 'Tingkat yang dipilih tidak valid.',
            'tahun.required' => 'Tahun harus diisi.',
            'kegiatan.required' => 'Kegiatan harus diisi.',
        ]);

        try { // jika sukses menambahkan data

            // simpan data
            PrestasiMahasiswa::create([
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'predikat' => $request->predikat,
                'tingkat' => $request->tingkat,
                'tahun' => $request->tahun,
                'kegiatan' => $request->kegiatan,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('prestasiMahasiswa.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model PrestasiMahasiswa berdasarkan id
            $prestasiMahasiswa = PrestasiMahasiswa::findOrFail($id);

            // data tingkat dalam array
            $tingkats = [
                'Internasional' => 'Internasional',
                'Nasional' => 'Nasional',
                'Daerah/Provinsi' => 'Daerah/Provinsi',
                'Wilayah' => 'Wilayah',
                'Regional' => 'Regional',
                'UNG' => 'UNG',
            ];

            // tampilkan halaman
            return view('admin.pages.mahasiswa-dan-alumni.prestasi-mahasiswa.form', [
                'icon' => 'edit',
                'title' => 'Prestasi Mahasiswa',
                'subtitle' => 'Edit Prestasi Mahasiswa',
                'active' => 'prestasi-mahasiswa',
                'prestasiMahasiswa' => $prestasiMahasiswa,
                'tingkats' => $tingkats,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // data tingkat dalam array
        $tingkats = [
            'Internasional' => 'Internasional',
            'Nasional' => 'Nasional',
            'Daerah/Provinsi' => 'Daerah/Provinsi',
            'Wilayah' => 'Wilayah',
            'Regional' => 'Regional',
            'UNG' => 'UNG',
        ];

        // validasi data yang dikirim
        $request->validate([
            'nama_mahasiswa' => 'required',
            'predikat' => 'required',
            'tingkat' => 'required|in:' . implode(',', array_keys($tingkats)),
            'kegiatan' => 'required',
        ], [
            'nama_mahasiswa.required' => 'Nama Mahasiswa harus diisi.',
            'predikat.required' => 'Predikat harus diisi.',
            'tingkat.required' => 'Tingkat harus dipilih.',
            'tingkat.in' => 'Tingkat yang dipilih tidak valid.',
            'kegiatan.required' => 'Kegiatan harus diisi.',
        ]);

        try { // jika id ditemukan
            // ambil data dari model PrestasiMahasiswa berdasarkan id
            $prestasiMahasiswa = PrestasiMahasiswa::findOrFail($id);

            $tahun = $request->tahun;
            $tahun_lama = $request->tahun_lama;

            if ($tahun == null) {
                $prestasiMahasiswa->update([
                    'nama_mahasiswa' => $request->nama_mahasiswa,
                    'predikat' => $request->predikat,
                    'tingkat' => $request->tingkat,
                    'tahun' => $tahun_lama,
                    'kegiatan' => $request->kegiatan,
                    'updated_by' => auth()->user()->id,
                ]);
            } else {
                $prestasiMahasiswa->update([
                    'nama_mahasiswa' => $request->nama_mahasiswa,
                    'predikat' => $request->predikat,
                    'tingkat' => $request->tingkat,
                    'tahun' => $request->tahun,
                    'kegiatan' => $request->kegiatan,
                    'updated_by' => auth()->user()->id,
                ]);
            }

            return redirect()->route('prestasiMahasiswa.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model PrestasiMahasiswa berdasarkan id
            $prestasiMahasiswa = PrestasiMahasiswa::findOrFail($id);

            // hapus data dari table prestasi_mahasiswas
            $prestasiMahasiswa->delete();

            return redirect()->route('prestasiMahasiswa.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
