<?php

namespace App\Http\Controllers;

use App\Models\DokumenKurikulum;
use Illuminate\Http\Request;

class DokumenKurikulumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokumenKurikulums = DokumenKurikulum::with('createdBy')->orderBy('created_at', 'desc')->get();

        // tampilkan halaman
        return view('admin.pages.akademik.dokumen-kurikulum.index', [
            'icon' => 'fa-solid fa-graduation-cap',
            'title' => 'Dokumen Kurikulum',
            'subtitle' => 'Daftar Dokumen Kurikulum',
            'active' => 'dokumen-kurikulum',
            'dokumenKurikulums' => $dokumenKurikulums
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'link_gdrive' => 'required',
        ], [
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.string' => 'Keterangan harus berupa string',
            'keterangan.max' => 'Keterangan maksimal 255 karakter',
            'link_gdrive.required' => 'Dokumen harus diisi',
        ]);

        try { // jika sukses menambahkan data
            DokumenKurikulum::create([
                'keterangan' => $request->keterangan,
                'link_gdrive' => $request->link_gdrive,
                'active' => 0,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('dokumenKurikulum.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('dokumenKurikulum.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function updateStatus(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'id' => 'required|exists:dokumen_kurikulums,id',
            'status' => 'required|boolean',
        ]);

        try {
            // Perbarui status dokumen kurikulum
            $dokumenKurikulum = DokumenKurikulum::findOrFail($request->id);
            $dokumenKurikulum->active = $request->status;
            $dokumenKurikulum->save();

            return response()->json(['message' => 'Status berhasil diperbarui', 'status' => $dokumenKurikulum->active], 200);
        } catch (\Exception $e) {
            // Tangani error jika terjadi kesalahan saat menyimpan
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui status'], 500);
        }
    }

    public function destroy($id)
    {
        try { // jika sukses hapus data
            // ambil data
            $dokumenKurikulum = DokumenKurikulum::findOrFail($id);

            // hapus data
            $dokumenKurikulum->delete();

            return redirect()->route('dokumenKurikulum.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dokumenKurikulum.index')->with('error', 'Data gagal dihapus. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal hapus data
            return redirect()->route('dokumenKurikulum.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
