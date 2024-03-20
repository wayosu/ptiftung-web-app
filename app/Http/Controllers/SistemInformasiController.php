<?php

namespace App\Http\Controllers;

use App\Models\SistemInformasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SistemInformasiController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $sistemInformasis = SistemInformasi::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $sistemInformasis = $sistemInformasis->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($sistemInformasis)
                ->addColumn('aksi', function ($sistemInformasis) {
                    return view('admin.pages.fasilitas.sistem-informasi.tombol-aksi', compact('sistemInformasis'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.fasilitas.sistem-informasi.index', [
            'icon' => 'fa-regular fa-hospital',
            'title' => 'Sistem Informasi',
            'subtitle' => 'Daftar Sistem Informasi',
            'active' => 'sistem-informasi',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.fasilitas.sistem-informasi.form', [
            'icon' => 'plus',
            'title' => 'Sistem Informasi',
            'subtitle' => 'Tambah Sistem Informasi',
            'active' => 'sistem-informasi',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'sistem_informasi' => 'required',
            'link' => 'required',
        ], [
            'sistem_informasi.required' => 'Nama sistem informasi harus diisi.',
            'link.required' => 'Tautan sistem informasi harus diisi.',
        ]);

        try { // jika sukses menambahkan data

            // simpan data
            SistemInformasi::create([
                'sistem_informasi' => $request->sistem_informasi,
                'link' => $request->link,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('sistemInformasi.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika bermasalah menambahkan data
            return redirect()->route('sistemInformasi.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try {
            // ambil data dari model SistemInformasi berdasarkan id
            $sistemInformasi = SistemInformasi::findOrFail($id);

             // tampilkan halaman
            return view('admin.pages.fasilitas.sistem-informasi.form', [
                'icon' => 'edit',
                'title' => 'Sistem Informasi',
                'subtitle' => 'Edit Sistem Informasi',
                'active' => 'sistem-informasi',
                'sistemInformasi' => $sistemInformasi,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('sistemInformasi.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('sistemInformasi.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'sistem_informasi' => 'required',
            'link' => 'required',
        ], [
            'sistem_informasi.required' => 'Nama sistem informasi harus diisi.',
            'link.required' => 'Tautan sistem informasi harus diisi.',
        ]);

        try { // jika id ditemukan lakukan update
            // ambil data dari model SistemInformasi berdasarkan id
            $sistemInformasi = SistemInformasi::findOrFail($id);

            // update data
            $sistemInformasi->update([
                'sistem_informasi' => $request->sistem_informasi,
                'link' => $request->link,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('sistemInformasi.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('sistemInformasi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('sistemInformasi.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model SistemInformasi berdasarkan id
            $sistemInformasi = SistemInformasi::findOrFail($id);

            // hapus data dari table SistemInformasi
            $sistemInformasi->delete();

            return redirect()->route('sistemInformasi.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('sistemInformasi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('sistemInformasi.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
