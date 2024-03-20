<?php

namespace App\Http\Controllers;

use App\Models\Sarana;
use App\Models\SaranaKategori;
use App\Models\SaranaTemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class SaranaController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $saranas = Sarana::with(['createdBy', 'saranaKategori'])->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $saranas = $saranas->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($saranas)
                ->addColumn('aksi', function ($saranas) {
                    return view('admin.pages.fasilitas.sarana.tombol-aksi', compact('saranas'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.fasilitas.sarana.index', [
            'icon' => 'fa-regular fa-hospital',
            'title' => 'Sarana',
            'subtitle' => 'Daftar Sarana',
            'active' => 'sarana',
        ]);
    }

    public function create()
    {
        try {
            // ambil data dari mode SaranaKategori. lalu hanya ambil id dan sarana_kategori saja
            $saranaKategoris = SaranaKategori::orderBy('sarana_kategori', 'asc')->get(['id', 'sarana_kategori']);

            // tampilkan halaman
            return view('admin.pages.fasilitas.sarana.form', [
                'icon' => 'plus',
                'title' => 'Sarana',
                'subtitle' => 'Tambah Sarana',
                'active' => 'sarana',
                'saranaKategoris' => $saranaKategoris
            ]);
        } catch (\Exception $e) {  // jika bermasalah mengambil data
            return redirect()->route('sarana.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'keterangan' => 'required|unique:saranas,keterangan',
            'sarana_kategori_id' => 'required',
        ], [
            'keterangan.required' => 'Keterangan harus diisi.',
            'keterangan.unique' => 'Keterangan sudah ada.',
            'sarana_kategori_id.required' => 'Kategori Sarana harus dipilih.',
        ]);

        try { // jika sukses menambahkan data

            // simpan data
            $sarana = Sarana::create([
                'keterangan' => $request->keterangan,
                'slug' => Str::slug($request->keterangan),
                'sarana_kategori_id' => $request->sarana_kategori_id,
                'created_by' => auth()->user()->id,
            ]);

            // ambil data dari table sarana temporary image
            $saranaTemporaryImages = SaranaTemporaryImage::where('user_id', auth()->user()->id)->get();

            // looping data dari table sarana temporary image
            foreach ($saranaTemporaryImages as $saranaTemporaryImage) {
                // salin file dari sarana tmp ke sarana
                Storage::copy('fasilitas/sarana/tmp/' . $saranaTemporaryImage->file, 'fasilitas/sarana/' . $saranaTemporaryImage->file);

                // simpan data gambar ke database
                $sarana->saranaImages()->create([
                    'gambar' => $saranaTemporaryImage->file,
                ]);

                // hapus file dari sarana tmp
                Storage::delete('fasilitas/sarana/tmp/' . $saranaTemporaryImage->file);
            }

            // hapus semua data di table sarana temporary image berdasarkan user_id
            SaranaTemporaryImage::where('user_id', auth()->user()->id)->delete();

            return redirect()->route('sarana.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('sarana.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model Sarana berdasarkan id
            $sarana = Sarana::findOrFail($id);

            // ambil data dari mode SaranaKategori. lalu hanya ambil id dan sarana_kategori saja
            $saranaKategoris = SaranaKategori::orderBy('sarana_kategori', 'asc')->get(['id', 'sarana_kategori']);

            // tampilkan halaman
            return view('admin.pages.fasilitas.sarana.form', [
                'icon' => 'edit',
                'title' => 'Sarana',
                'subtitle' => 'Edit Sarana',
                'active' => 'sarana',
                'sarana' => $sarana,
                'saranaKategoris' => $saranaKategoris
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('sarana.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('sarana.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'keterangan' => 'required|unique:saranas,keterangan,' . $id,
            'sarana_kategori_id' => 'required',
        ], [
            'keterangan.required' => 'Keterangan harus diisi.',
            'sarana_kategori_id.required' => 'Kategori Sarana harus dipilih.',
        ]);

        try { // jika sukses update data
            // ambil data dari model Sarana berdasarkan id
            $sarana = Sarana::findOrFail($id);

            // update data dari model Sarana
            $sarana->update([
                'keterangan' => $request->keterangan,
                'slug' => Str::slug($request->keterangan),
                'sarana_kategori_id' => $request->sarana_kategori_id,
            ]);

            // ambil data dari table sarana temporary image
            $saranaTemporaryImages = SaranaTemporaryImage::where('user_id', auth()->user()->id)->get();

            // looping data dari table sarana temporary image
            foreach ($saranaTemporaryImages as $saranaTemporaryImage) {
                // salin file dari sarana tmp ke sarana
                Storage::copy('fasilitas/sarana/tmp/' . $saranaTemporaryImage->file, 'fasilitas/sarana/' . $saranaTemporaryImage->file);

                // simpan data gambar ke database
                $sarana->saranaImages()->create([
                    'gambar' => $saranaTemporaryImage->file,
                ]);

                // hapus file dari sarana tmp
                Storage::delete('fasilitas/sarana/tmp/' . $saranaTemporaryImage->file);
            }

            // hapus semua data di table sarana temporary image berdasarkan user_id
            SaranaTemporaryImage::where('user_id', auth()->user()->id)->delete();

            return redirect()->route('sarana.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('sarana.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('sarana.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model Sarana berdasarkan id
            $sarana = Sarana::findOrFail($id);

            // looping data dari table sarana image
            foreach ($sarana->saranaImages as $saranaImage) {
                // hapus file dari storage/penyimpanan
                if (Storage::exists('fasilitas/sarana/' . $saranaImage->gambar)) {
                    Storage::delete('fasilitas/sarana/' . $saranaImage->gambar);
                }

                // hapus data dari table sarana image
                $saranaImage->delete();
            }

            // hapus data dari table Sarana
            $sarana->delete();

            return redirect()->route('sarana.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('sarana.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('sarana.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
