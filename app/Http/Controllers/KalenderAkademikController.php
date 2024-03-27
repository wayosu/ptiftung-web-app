<?php

namespace App\Http\Controllers;

use App\Models\KalenderAkademik;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KalenderAkademikController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $kalenderAkademiks = KalenderAkademik::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $kalenderAkademiks = $kalenderAkademiks->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($kalenderAkademiks)
                ->addColumn('aksi', function ($kalenderAkademiks) {
                    return view('admin.pages.akademik.kalender-akademik.tombol-aksi', compact('kalenderAkademiks'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.akademik.kalender-akademik.index', [
            'icon' => 'fa-solid fa-graduation-cap',
            'title' => 'Kalender Akademik',
            'subtitle' => 'Daftar Kalender Akademik',
            'active' => 'kalender-akademik',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.akademik.kalender-akademik.form', [
            'icon' => 'plus',
            'title' => 'Kalender Akademik',
            'subtitle' => 'Tambah Kegiatan',
            'active' => 'kalender-akademik',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'kegiatan' => 'required',
            'waktu' => 'required',
        ], [
            'kegiatan.required' => 'Kegiatan harus diisi.',
            'waktu.required' => 'Waktu harus diisi.',
        ]);

        try { // jika sukses menambahkan data
            KalenderAkademik::create([
                'kegiatan' => $request->kegiatan,
                'waktu' => $request->waktu,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('kalenderAkademik.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('kalenderAkademik.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika sukses mengambil data
            // cari dan ambil data
            $kalenderAkademik = KalenderAkademik::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.akademik.kalender-akademik.form', [
                'icon' => 'edit',
                'title' => 'Kalender Akademik',
                'subtitle' => 'Edit Kegiatan',
                'active' => 'kalender-akademik',
                'kalenderAkademik' => $kalenderAkademik,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kalenderAkademik.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kalenderAkademik.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'kegiatan' => 'required',
            'waktu' => 'required',
        ], [
            'kegiatan.required' => 'Kegiatan harus diisi.',
            'waktu.required' => 'Waktu harus diisi.',
        ]);

        try { // jika sukses update data
            // cari data
            $kalenderAkademik = KalenderAkademik::findOrFail($id);

            $kalenderAkademik->update([
                'kegiatan' => $request->kegiatan,
                'waktu' => $request->waktu,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('kalenderAkademik.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kalenderAkademik.index')->with('error', 'Data gagal diperbarui. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal update data
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika sukses hapus data
            // ambil data
            $kalenderAkademik = KalenderAkademik::findOrFail($id);

            // hapus data
            $kalenderAkademik->delete();

            return redirect()->route('kalenderAkademik.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kalenderAkademik.index')->with('error', 'Data gagal dihapus. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal hapus data
            return redirect()->route('kalenderAkademik.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
