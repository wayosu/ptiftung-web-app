<?php

namespace App\Http\Controllers;

use App\Models\SeminarDanKompetisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SeminarDanKompetisiController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $seminarDanKompetisis = SeminarDanKompetisi::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $seminarDanKompetisis = $seminarDanKompetisis->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($seminarDanKompetisis)
                ->addColumn('aksi', function ($seminarDanKompetisis) {
                    return view('admin.pages.mahasiswa-dan-alumni.peluang-mahasiswa.seminar-dan-kompetisi.tombol-aksi', compact('seminarDanKompetisis'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.peluang-mahasiswa.seminar-dan-kompetisi.index', [
            'icon' => 'fas fa-users-rays',
            'title' => 'Seminar Dan Kompetisi',
            'subtitle' => 'Daftar Informasi Seminar Dan Kompetisi',
            'active' => 'seminar-dan-kompetisi',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.peluang-mahasiswa.seminar-dan-kompetisi.form', [
            'icon' => 'plus',
            'title' => 'Seminar Dan Kompetisi',
            'subtitle' => 'Tambah Informasi Seminar Dan Kompetisi',
            'active' => 'seminar-dan-kompetisi',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required' => 'Judul harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'gambar.required' => 'Gambar harus diisi!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.mimes' => 'File harus berupa jpeg, png, jpg!',
            'gambar.max' => 'File tidak boleh lebih dari 2 MB!',
        ]);

        try { // jika data valid
            if ($request->hasFile('gambar')) {
                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('gambar')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'mahasiswa-dan-alumni/peluang-mahasiswa/seminar-dan-kompetisi';
                $request->file('gambar')->storeAs($storePath, $nameFile);

                // simpan data
                SeminarDanKompetisi::create([
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul),
                    'deskripsi' => $request->deskripsi,
                    'gambar' => $nameFile,
                    'created_by' => auth()->user()->id,
                ]);

                return redirect()->route('seminarDanKompetisi.index')->with('success', 'Data berhasil ditambahkan.');
            } else {
                return redirect()->route('seminarDanKompetisi.index')->with('error', 'Data gagal ditambahkan. File gambar tidak boleh kosong!');
            }
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('seminarDanKompetisi.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
             // ambil data dari model SeminarDanKompetisi berdasarkan id
            $seminarDanKompetisi = SeminarDanKompetisi::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.mahasiswa-dan-alumni.peluang-mahasiswa.seminar-dan-kompetisi.form', [
                'icon' => 'edit',
                'title' => 'Seminar Dan Kompetisi',
                'subtitle' => 'Edit Informasi Seminar Dan Kompetisi',
                'active' => 'seminar-dan-kompetisis',
                'seminarDanKompetisi' => $seminarDanKompetisi,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('seminarDanKompetisi.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('seminarDanKompetisi.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required' => 'Judul harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.mimes' => 'File harus berupa jpeg, png, jpg!',
            'gambar.max' => 'File tidak boleh lebih dari 2 MB!',
        ]);

        try { // jika data valid
            // ambil data dari model SeminarDanKompetisi berdasarkan id
            $seminarDanKompetisi = SeminarDanKompetisi::findOrFail($id);

            if ($request->hasFile('gambar')) {
                // cek apakah ada file yang lama
                if (Storage::exists('mahasiswa-dan-alumni/peluang-mahasiswa/seminar-dan-kompetisi/' . $seminarDanKompetisi->gambar)) {
                    // hapus file
                    Storage::delete('mahasiswa-dan-alumni/peluang-mahasiswa/seminar-dan-kompetisi/' . $seminarDanKompetisi->gambar);
                }

                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('gambar')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'mahasiswa-dan-alumni/peluang-mahasiswa/seminar-dan-kompetisi';
                $request->file('gambar')->storeAs($storePath, $nameFile);

                $data = [
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul),
                    'deskripsi' => $request->deskripsi,
                    'gambar' => $nameFile,
                    'updated_by' => auth()->user()->id,
                ];
            } else {
                $data = [
                    'judul' => $request->judul,
                    'slug' => Str::slug($request->judul),
                    'deskripsi' => $request->deskripsi,
                    'updated_by' => auth()->user()->id,
                ];
            }

            $seminarDanKompetisi->update($data);

            return redirect()->route('seminarDanKompetisi.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('seminarDanKompetisi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal mengupdate data
            return redirect()->route('seminarDanKompetisi.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model SeminarDanKompetisi berdasarkan id
            $seminarDanKompetisi = SeminarDanKompetisi::findOrFail($id);

            // hapus file dari storage/penyimpanan
            if (Storage::exists('mahasiswa-dan-alumni/peluang-mahasiswa/seminar-dan-kompetisi/' . $seminarDanKompetisi->gambar)) {
                Storage::delete('mahasiswa-dan-alumni/peluang-mahasiswa/seminar-dan-kompetisi/' . $seminarDanKompetisi->gambar);
            }

            // hapus data dari table seminar dan kompetisi
            $seminarDanKompetisi->delete();

            return redirect()->route('seminarDanKompetisi.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('seminarDanKompetisi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('seminarDanKompetisi.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
