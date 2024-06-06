<?php

namespace App\Http\Controllers;

use App\Models\KegiatanPerkuliahan;
use App\Models\KegiatanPerkuliahanTemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class KegiatanPerkuliahanController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $kegiatanPerkuliahans = KegiatanPerkuliahan::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $kegiatanPerkuliahans = $kegiatanPerkuliahans->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($kegiatanPerkuliahans)
                ->addColumn('aksi', function ($kegiatanPerkuliahans) {
                    return view('admin.pages.akademik.kegiatan-perkuliahan.tombol-aksi', compact('kegiatanPerkuliahans'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.akademik.kegiatan-perkuliahan.index', [
            'icon' => 'fa-solid fa-graduation-cap',
            'title' => 'Kegiatan Perkuliahan',
            'subtitle' => 'Daftar Kegiatan Perkuliahan',
            'active' => 'kegiatan-perkuliahan',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.akademik.kegiatan-perkuliahan.form', [
            'icon' => 'plus',
            'title' => 'Kegiatan Perkuliahan',
            'subtitle' => 'Tambah Kegiatan Perkuliahan',
            'active' => 'kegiatan-perkuliahan',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'judul' => 'required|unique:kegiatan_perkuliahans,judul',
            'deskripsi' => 'required',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'program_studi' => 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI'
        ], [
            'judul.required' => 'Judul harus diisi.',
            'judul.unique' => 'Judul sudah ada.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'thumbnail.required' => 'Thumbnail harus diisi.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.mimes' => 'File harus berupa jpg, jpeg, png.',
            'thumbnail.max' => 'Ukuran file maksimal 2 MB.',
            'program_studi.required' => 'Program Studi harus dipilih.',
            'program_studi.in' => 'Program Studi tidak valid.'
        ]);

        try { // jika sukses menambahkan data

            if ($request->hasFile('thumbnail')) {
                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'akademik/kegiatan-perkuliahan/thumbnail';
                $request->file('thumbnail')->storeAs($storePath, $nameFile);

                // simpan data
                $kegiatanPerkuliahan = KegiatanPerkuliahan::create([
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul),
                    'deskripsi' => $request->deskripsi,
                    'thumbnail' => $nameFile,
                    'link_video' => $request->link_video,
                    'program_studi' => $request->program_studi,
                    'created_by' => auth()->user()->id,
                ]);

                // ambil data dari table kegiatan perkuliahan temporary image
                $kegiatanPerkuliahanTemporaryImages = KegiatanPerkuliahanTemporaryImage::where('user_id', auth()->user()->id)->get();

                // looping data dari table kegiatan perkuliahan temporary image
                foreach ($kegiatanPerkuliahanTemporaryImages as $kegiatanPerkuliahanTemporaryImage) {
                    // salin file dari kegiatan perkuliahan tmp ke kegiatan perkuliahan
                    Storage::copy('akademik/kegiatan-perkuliahan/tmp/' . $kegiatanPerkuliahanTemporaryImage->file, 'akademik/kegiatan-perkuliahan/' . $kegiatanPerkuliahanTemporaryImage->file);

                    // simpan data gambar ke database
                    $kegiatanPerkuliahan->kegiatanPerkuliahanImages()->create([
                        'gambar' => $kegiatanPerkuliahanTemporaryImage->file,
                    ]);

                    // hapus file dari kegiatan perkuliahan tmp
                    Storage::delete('akademik/kegiatan-perkuliahan/tmp/' . $kegiatanPerkuliahanTemporaryImage->file);
                }

                // hapus semua data di table kegiatan perkuliahan temporary image berdasarkan user_id
                KegiatanPerkuliahanTemporaryImage::where('user_id', auth()->user()->id)->delete();

                return redirect()->route('kegiatanPerkuliahan.index')->with('success', 'Data berhasil ditambahkan.');
            } else {
                return redirect()->route('kegiatanPerkuliahan.index')->with('error', 'Data gagal ditambahkan. File thumbnail harus diisi!');
            }
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('kegiatanPerkuliahan.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model KegiatanPerkuliahan berdasarkan id
            $kegiatanPerkuliahan = KegiatanPerkuliahan::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.akademik.kegiatan-perkuliahan.form', [
                'icon' => 'edit',
                'title' => 'Kegiatan Perkuliahan',
                'subtitle' => 'Edit Kegiatan Perkuliahan',
                'active' => 'kegiatan-perkuliahan',
                'kegiatanPerkuliahan' => $kegiatanPerkuliahan,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kegiatanPerkuliahan.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kegiatanPerkuliahan.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|unique:kegiatan_perkuliahans,judul,' . $id,
            'deskripsi' => 'required',
            'thumbnail' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'program_studi' => 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI'
        ], [
            'judul.required' => 'Judul harus diisi.',
            'judul.unique' => 'Judul sudah ada.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.mimes' => 'File harus berupa jpg, jpeg, png.',
            'thumbnail.max' => 'Ukuran file maksimal 2 MB.',
            'program_studi.required' => 'Program Studi harus dipilih.',
            'program_studi.in' => 'Program Studi tidak valid.'
        ]);

        try { // jika sukses update data
            // ambil data dari model KegiatanPerkuliahan berdasarkan id
            $kegiatanPerkuliahan = KegiatanPerkuliahan::findOrFail($id);

            if ($request->hasFile('thumbnail')) {
                // cek apakah ada file yang lama
                if (Storage::exists('akademik/kegiatan-perkuliahan/thumbnail/' . $kegiatanPerkuliahan->thumbnail)) {
                    // hapus file
                    Storage::delete('akademik/kegiatan-perkuliahan/thumbnail/' . $kegiatanPerkuliahan->thumbnail);
                }

                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('thumbnail')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'akademik/kegiatan-perkuliahan/thumbnail';
                $request->file('thumbnail')->storeAs($storePath, $nameFile);

                $data = [
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul),
                    'deskripsi' => $request->deskripsi,
                    'thumbnail' => $nameFile,
                    'link_video' => $request->link_video,
                    'program_studi' => $request->program_studi,
                    'created_by' => auth()->user()->id,
                ];
            } else {
                $data = [
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul),
                    'deskripsi' => $request->deskripsi,
                    'link_video' => $request->link_video,
                    'program_studi' => $request->program_studi,
                    'created_by' => auth()->user()->id,
                ];
            }

            // update data
            $kegiatanPerkuliahan->update($data);

            // ambil data dari table kegiatan perkuliahan temporary image
            $kegiatanPerkuliahanTemporaryImages = KegiatanPerkuliahanTemporaryImage::where('user_id', auth()->user()->id)->get();

            // looping data dari table kegiatan perkuliahan temporary image
            foreach ($kegiatanPerkuliahanTemporaryImages as $kegiatanPerkuliahanTemporaryImage) {
                // salin file dari kegiatan perkuliahan tmp ke kegiatan perkuliahan
                Storage::copy('akademik/kegiatan-perkuliahan/tmp/' . $kegiatanPerkuliahanTemporaryImage->file, 'akademik/kegiatan-perkuliahan/' . $kegiatanPerkuliahanTemporaryImage->file);

                // simpan data gambar ke database
                $kegiatanPerkuliahan->kegiatanPerkuliahanImages()->create([
                    'gambar' => $kegiatanPerkuliahanTemporaryImage->file,
                ]);

                // hapus file dari kegiatan perkuliahan tmp
                Storage::delete('akademik/kegiatan-perkuliahan/tmp/' . $kegiatanPerkuliahanTemporaryImage->file);
            }

            // hapus semua data di table kegiatan perkuliahan temporary image berdasarkan user_id
            KegiatanPerkuliahanTemporaryImage::where('user_id', auth()->user()->id)->delete();

            return redirect()->route('kegiatanPerkuliahan.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kegiatanPerkuliahan.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('kegiatanPerkuliahan.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model KegiatanPerkuliahan berdasarkan id
            $kegiatanPerkuliahan = KegiatanPerkuliahan::findOrFail($id);

            // hapus file dari storage/penyimpanan
            if (Storage::exists('akademik/kegiatan-perkuliahan/thumbnail/' . $kegiatanPerkuliahan->thumbnail)) {
                Storage::delete('akademik/kegiatan-perkuliahan/thumbnail/' . $kegiatanPerkuliahan->thumbnail);
            }

            // loop data dari table kegiatan perkuliahan image
            foreach ($kegiatanPerkuliahan->kegiatanPerkuliahanImages as $kegiatanPerkuliahanImage) {
                // hapus file dari storage/penyimpanan
                if (Storage::exists('akademik/kegiatan-perkuliahan/' . $kegiatanPerkuliahanImage->gambar)) {
                    Storage::delete('akademik/kegiatan-perkuliahan/' . $kegiatanPerkuliahanImage->gambar);
                }

                // hapus gambar dari table kegiatan perkuliahan image
                $kegiatanPerkuliahanImage->delete();
            }

            // hapus data dari table kegiatan perkuliahan
            $kegiatanPerkuliahan->delete();

            return redirect()->route('kegiatanPerkuliahan.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kegiatanPerkuliahan.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('kegiatanPerkuliahan.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
