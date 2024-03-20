<?php

namespace App\Http\Controllers;

use App\Models\SaranaKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class SaranaKategoriController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $saranaKategoris = SaranaKategori::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $saranaKategoris = $saranaKategoris->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($saranaKategoris)
                ->addColumn('aksi', function ($saranaKategoris) {
                    return view('admin.pages.fasilitas.kategori-sarana.tombol-aksi', compact('saranaKategoris'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.fasilitas.kategori-sarana.index', [
            'icon' => 'fa-regular fa-hospital',
            'title' => 'Kategori Sarana',
            'subtitle' => 'Daftar Kategori Sarana',
            'active' => 'kategori-sarana',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.fasilitas.kategori-sarana.form', [
            'icon' => 'plus',
            'title' => 'Kategori Sarana',
            'subtitle' => 'Tambah Kategori Sarana',
            'active' => 'kategori-sarana',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'sarana_kategori' => 'required|unique:sarana_kategoris',
        ], [
            'sarana_kategori.required' => 'Kategori sarana harus diisi.',
            'sarana_kategori.unique' => 'Kategori sarana sudah ada.',
        ]);

        try { // jika sukses menambahkan data
            SaranaKategori::create([
                'sarana_kategori' => $request->sarana_kategori,
                'slug' => Str::slug($request->sarana_kategori),
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('kategoriSarana.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('kategoriSarana.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika sukses mengambil data
            // cari dan ambil data
            $saranaKategori = SaranaKategori::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.fasilitas.kategori-sarana.form', [
                'icon' => 'edit',
                'title' => 'Kategori Sarana',
                'subtitle' => 'Edit Kategori Sarana',
                'active' => 'kategori-sarana',
                'saranaKategori' => $saranaKategori,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kategoriSarana.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kategoriSarana.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'sarana_kategori' => 'required|unique:sarana_kategoris,sarana_kategori,' . $id,
        ], [
            'sarana_kategori.required' => 'Kategori Sarana harus diisi.',
            'sarana_kategori.unique' => 'Kategori Sarana sudah ada.',
        ]);

        try { // jika sukses update data
            // cari data
            $saranaKategori = SaranaKategori::findOrFail($id);

            $saranaKategori->update([
                'sarana_kategori' => $request->sarana_kategori,
                'slug' => Str::slug($request->sarana_kategori),
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('kategoriSarana.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kategoriSarana.index')->with('error', 'Data gagal diperbarui. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal update data
            return redirect()->route('kategoriSarana.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika sukses hapus data
            // ambil data
            $saranaKategori = SaranaKategori::findOrFail($id);

            // hapus gambar sarana image
            $saranas = $saranaKategori->saranas;
            foreach ($saranas as $sarana) {
                foreach ($sarana->saranaImages as $saranaImage) {
                    if (Storage::exists('fasilitas/sarana/' . $saranaImage->gambar)) {
                        Storage::delete('fasilitas/sarana/' . $saranaImage->gambar);
                    }
                }
            }

            // hapus data
            $saranaKategori->delete();

            return redirect()->route('kategoriSarana.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kategoriSarana.index')->with('error', 'Data gagal dihapus. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal hapus data
            return redirect()->route('kategoriSarana.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
