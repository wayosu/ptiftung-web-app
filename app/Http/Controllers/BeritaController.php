<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $beritas = Berita::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $beritas = $beritas->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($beritas)
                ->addColumn('aksi', function ($beritas) {
                    return view('admin.pages.konten.berita.tombol-aksi', compact('beritas'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.konten.berita.index', [
            'icon' => 'layout',
            'title' => 'Berita',
            'subtitle' => 'Daftar Berita',
            'active' => 'berita',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.konten.berita.form', [
            'icon' => 'plus',
            'title' => 'Berita',
            'subtitle' => 'Tambah Berita',
            'active' => 'berita',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required' => 'Judul harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'thumbnail.required' => 'Thumbnail harus diisi!',
            'thumbnail.image' => 'File harus berupa gambar!',
            'thumbnail.mimes' => 'File harus berupa jpeg, png, jpg!',
            'thumbnail.max' => 'File tidak boleh lebih dari 2 MB!',
        ]);

        try { // jika data valid
            if ($request->btn_submit == 'draft') {
                $status = 'draft';
            } else if ($request->btn_submit == 'published') {
                $status = 'published';
            } else {
                $status = 'draft';
            }

            if ($request->hasFile('thumbnail')) {
                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'konten/berita';
                $request->file('thumbnail')->storeAs($storePath, $nameFile);

                // simpan data
                Berita::create([
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul),
                    'deskripsi' => $request->deskripsi,
                    'thumbnail' => $nameFile,
                    'status' => $status,
                    'created_by' => auth()->user()->id,
                ]);

                return redirect()->route('berita.index')->with('success', 'Data berhasil ditambahkan.');
            } else {
                return redirect()->route('berita.index')->with('error', 'Data gagal ditambahkan. File thumbnail tidak boleh kosong!');
            }
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('berita.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
             // ambil data dari model Berita berdasarkan id
            $berita = Berita::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.konten.berita.form', [
                'icon' => 'edit',
                'title' => 'Berita',
                'subtitle' => 'Edit Berita',
                'active' => 'berita',
                'berita' => $berita,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('berita.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('berita.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'thumbnail' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required' => 'Judul harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'thumbnail.image' => 'File harus berupa gambar!',
            'thumbnail.mimes' => 'File harus berupa jpeg, png, jpg!',
            'thumbnail.max' => 'File tidak boleh lebih dari 2 MB!',
        ]);

        try { // jika data valid
            // ambil data dari model Berita berdasarkan id
            $berita = Berita::findOrFail($id);

            // Tetapkan status berita
            $status = $berita->status;
            if ($berita->status == 'draft') {
                if ($request->btn_submit == 'published') {
                    $status = 'published';
                } else if ($request->btn_submit == 'draft') {
                    $status = 'draft';
                }
            }

            // Jika ada perubahan thumbnail, simpan thumbnail yang baru
            if ($request->hasFile('thumbnail')) {
                // Namakan file baru
                $nameFile = uniqid() . time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();

                // Simpan file ke storage/penyimpanan
                $storePath = 'konten/berita';
                $request->file('thumbnail')->storeAs($storePath, $nameFile);

                // Hapus thumbnail lama jika ada
                if ($berita->thumbnail) {
                    // cek apakah ada file di storage
                    if (Storage::exists('konten/berita/' . $berita->thumbnail)) {
                        // hapus file
                        Storage::delete('konten/berita/' . $berita->thumbnail);
                    }
                }

                // Perbarui thumbnail dengan yang baru
                $berita->thumbnail = $nameFile;
            }

            // Perbarui data berita
            $berita->judul = $request->judul;
            $berita->slug = Str::slug($request->judul);
            $berita->deskripsi = $request->deskripsi;
            $berita->status = $status;
            $berita->updated_by = auth()->user()->id;
            $berita->save();

            return redirect()->route('berita.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('berita.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal mengupdate data
            return redirect()->route('berita.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model Berita berdasarkan id
            $berita = Berita::findOrFail($id);

            // hapus file dari storage/penyimpanan
            if (Storage::exists('konten/berita/' . $berita->thumbnail)) {
                Storage::delete('konten/berita/' . $berita->thumbnail);
            }

            // hapus data dari table berita
            $berita->delete();

            return redirect()->route('berita.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('berita.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('berita.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
