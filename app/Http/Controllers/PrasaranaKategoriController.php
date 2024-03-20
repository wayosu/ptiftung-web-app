<?php

namespace App\Http\Controllers;

use App\Models\PrasaranaKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class PrasaranaKategoriController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $prasaranaKategoris = PrasaranaKategori::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $prasaranaKategoris = $prasaranaKategoris->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($prasaranaKategoris)
                ->addColumn('aksi', function ($prasaranaKategoris) {
                    return view('admin.pages.fasilitas.kategori-prasarana.tombol-aksi', compact('prasaranaKategoris'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.fasilitas.kategori-prasarana.index', [
            'icon' => 'fa-regular fa-hospital',
            'title' => 'Kategori Prasarana',
            'subtitle' => 'Daftar Kategori Prasarana',
            'active' => 'kategori-prasarana',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.fasilitas.kategori-prasarana.form', [
            'icon' => 'plus',
            'title' => 'Kategori Prasarana',
            'subtitle' => 'Tambah Kategori Prasarana',
            'active' => 'kategori-prasarana',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'prasarana_kategori' => 'required|unique:prasarana_kategoris',
        ], [
            'prasarana_kategori.required' => 'Kategori Prasarana harus diisi.',
            'prasarana_kategori.unique' => 'Kategori Prasarana sudah ada.',
        ]);

        try { // jika sukses menambahkan data
            PrasaranaKategori::create([
                'prasarana_kategori' => $request->prasarana_kategori,
                'slug' => Str::slug($request->prasarana_kategori),
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('kategoriPrasarana.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('kategoriPrasarana.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika sukses mengambil data
            // cari dan ambil data
            $prasaranaKategori = PrasaranaKategori::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.fasilitas.kategori-prasarana.form', [
                'icon' => 'edit',
                'title' => 'Kategori Prasarana',
                'subtitle' => 'Edit Kategori Prasarana',
                'active' => 'kategori-prasarana',
                'prasaranaKategori' => $prasaranaKategori,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kategoriPrasarana.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kategoriPrasarana.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'prasarana_kategori' => 'required|unique:prasarana_kategoris,prasarana_kategori,' . $id,
        ], [
            'prasarana_kategori.required' => 'Kategori Prasarana harus diisi.',
            'prasarana_kategori.unique' => 'Kategori Prasarana sudah ada.',
        ]);

        try { // jika sukses update data
            // cari data
            $prasaranaKategori = PrasaranaKategori::findOrFail($id);

            $prasaranaKategori->update([
                'prasarana_kategori' => $request->prasarana_kategori,
                'slug' => Str::slug($request->prasarana_kategori),
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('kategoriPrasarana.index')->with('success', 'Data berhasil diupdate.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kategoriPrasarana.index')->with('error', 'Data gagal diupdate. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal update data
            return redirect()->route('kategoriPrasarana.index')->with('error', 'Data gagal diupdate!');
        }
    }

    public function destroy($id)
    {
        try { // jika sukses hapus data
            // ambil data
            $prasaranaKategori = PrasaranaKategori::findOrFail($id);

            // hapus gambar prasarana image
            $prasaranas = $prasaranaKategori->prasaranas;
            foreach ($prasaranas as $prasarana) {
                foreach ($prasarana->saranaImages as $prasaranaImage) {
                    if (Storage::exists('fasilitas/prasarana/' . $prasaranaImage->gambar)) {
                        Storage::delete('fasilitas/prasarana/' . $prasaranaImage->gambar);
                    }
                }
            }

            // hapus data
            $prasaranaKategori->delete();

            return redirect()->route('kategoriPrasarana.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kategoriPrasarana.index')->with('error', 'Data gagal dihapus. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal hapus data
            return redirect()->route('kategoriPrasarana.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
