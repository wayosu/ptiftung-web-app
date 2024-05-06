<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $jurnals = Jurnal::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $jurnals = $jurnals->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($jurnals)
                ->addColumn('aksi', function ($jurnals) {
                    return view('admin.pages.konten.jurnal.tombol-aksi', compact('jurnals'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.konten.jurnal.index', [
            'icon' => 'layout',
            'title' => 'Jurnal',
            'subtitle' => 'Daftar Jurnal',
            'active' => 'jurnal',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'judul_jurnal' => 'required',
            'link_jurnal' => 'required',
        ], [
            'judul_jurnal.required' => 'Judul Jurnal harus diisi!',
            'link_jurnal.required' => 'Link Jurnal harus diisi!',
        ]);

        try { // jika data valid
            // simpan data
            Jurnal::create([
                'judul_jurnal' => $request->judul_jurnal,
                'link_jurnal' => $request->link_jurnal,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('jurnal.index')->with('success', 'Data berhasil ditambahkan!');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('jurnal.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model Jurnal berdasarkan id
            $jurnal = Jurnal::findOrFail($id);

            // hapus data dari table jurnal
            $jurnal->delete();

            return redirect()->route('jurnal.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('jurnal.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('jurnal.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
