<?php

namespace App\Http\Controllers;

use App\Models\CapaianPembelajaran;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CapaianPembelajaranController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $capaianPembelajarans = CapaianPembelajaran::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $capaianPembelajarans = $capaianPembelajarans->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($capaianPembelajarans)
                ->addColumn('aksi', function ($capaianPembelajarans) {
                    return view('admin.pages.akademik.capaian-pembelajaran.tombol-aksi', compact('capaianPembelajarans'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.akademik.capaian-pembelajaran.index', [
            'icon' => 'fa-solid fa-graduation-cap',
            'title' => 'Capaian Pembelajaran',
            'subtitle' => 'Daftar Capaian Pembelajaran',
            'active' => 'capaian-pembelajaran',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.akademik.capaian-pembelajaran.form', [
            'icon' => 'plus',
            'title' => 'Capaian Pembelajaran',
            'subtitle' => 'Tambah Capaian Pembelajaran',
            'active' => 'capaian-pembelajaran',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'capaian_pembelajaran' => 'required',
        ], [
            'capaian_pembelajaran.required' => 'Capaian Pembelajaran harus diisi.',
        ]);

        try { // jika sukses menambahkan data
            CapaianPembelajaran::create([
                'capaian_pembelajaran' => $request->capaian_pembelajaran,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('capaianPembelajaran.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika sukses mengambil data
            // cari dan ambil data
            $capaianPembelajaran = CapaianPembelajaran::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.akademik.capaian-pembelajaran.form', [
                'icon' => 'edit',
                'title' => 'Capaian Pembelajaran',
                'subtitle' => 'Edit Capaian Pembelajaran',
                'active' => 'capaian-pembelajaran',
                'capaianPembelajaran' => $capaianPembelajaran,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'capaian_pembelajaran' => 'required',
        ], [
            'capaian_pembelajaran.required' => 'Kategori sarana harus diisi.',
        ]);

        try { // jika sukses update data
            // cari data
            $capaianPembelajaran = CapaianPembelajaran::findOrFail($id);

            $capaianPembelajaran->update([
                'capaian_pembelajaran' => $request->capaian_pembelajaran,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('capaianPembelajaran.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal diperbarui. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal update data
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika sukses hapus data
            // ambil data
            $capaianPembelajaran = CapaianPembelajaran::findOrFail($id);

            // hapus data
            $capaianPembelajaran->delete();

            return redirect()->route('capaianPembelajaran.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal dihapus. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal hapus data
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
