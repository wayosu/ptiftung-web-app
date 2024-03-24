<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KurikulumController extends Controller
{
    public function index(Request $request)
    {
        
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $kurikulum = Kurikulum::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $kurikulum = $kurikulum->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($kurikulum)
                ->addColumn('aksi', function ($kurikulum) {
                    return view('admin.pages.akademik.kurikulum.tombol-aksi', compact('kurikulum'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.akademik.kurikulum.index', [
            'icon' => 'fa-solid fa-graduation-cap',
            'title' => 'Kurikulum',
            'subtitle' => 'Daftar Kurikulum',
            'active' => 'kurikulum',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.akademik.kurikulum.form', [
            'icon' => 'plus',
            'title' => 'Kurikulum',
            'subtitle' => 'Tambah Kurikulum',
            'active' => 'kurikulum',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'kode_mk' => 'required',
            'nama_mk' => 'required',
            'sks' => 'required',
            'sifat' => 'required',
            'semester' => 'required',
        ], [
            'kode_mk.required' => 'Kode Mata Kuliah harus diisi',
            'nama_mk.required' => 'Nama Mata Kuliah harus diisi',
            'sks.required' => 'SKS harus diisi',
            'sifat.required' => 'Sifat harus dipilih',
            'semester.required' => 'Semester harus dipilih',
        ]);

        try { // jika sukses menambahkan data
            Kurikulum::create([
                'kode_mk' => $request->kode_mk,
                'nama_mk' => $request->nama_mk,
                'sks' => $request->sks,
                'sifat' => $request->sifat,
                'semester' => $request->semester,
                'prasyarat' => $request->prasyarat,
                'link_gdrive' => $request->link_gdrive,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('kurikulum.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika sukses mengambil data
            // cari dan ambil data
            $kurikulum = Kurikulum::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.akademik.kurikulum.form', [
                'icon' => 'edit',
                'title' => 'Kurikulum',
                'subtitle' => 'Edit Kurikulum',
                'active' => 'kurikulum',
                'kurikulum' => $kurikulum,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kurikulum.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kurikulum.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'kode_mk' => 'required',
            'nama_mk' => 'required',
            'sks' => 'required',
            'sifat' => 'required',
            'semester' => 'required',
        ], [
            'kode_mk.required' => 'Kode Mata Kuliah harus diisi',
            'nama_mk.required' => 'Nama Mata Kuliah harus diisi',
            'sks.required' => 'SKS harus diisi',
            'sifat.required' => 'Sifat harus dipilih',
            'semester.required' => 'Semester harus dipilih',
        ]);

        try { // jika sukses update data
            // cari data
            $kurikulum = Kurikulum::findOrFail($id);

            $kurikulum->update([
                'kode_mk' => $request->kode_mk,
                'nama_mk' => $request->nama_mk,
                'sks' => $request->sks,
                'sifat' => $request->sifat,
                'semester' => $request->semester,
                'prasyarat' => $request->prasyarat,
                'link_gdrive' => $request->link_gdrive,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('kurikulum.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal diperbarui. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal update data
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika sukses hapus data
            // ambil data
            $kurikulum = Kurikulum::findOrFail($id);

            // hapus data
            $kurikulum->delete();

            return redirect()->route('kurikulum.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal dihapus. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal hapus data
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
