<?php

namespace App\Http\Controllers;

use App\Models\KerjasamaLuarNegeri;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KerjasamaLuarNegeriController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $kerjasamaLuarNegeris = KerjasamaLuarNegeri::with('createdBy')->orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $kerjasamaLuarNegeris = $kerjasamaLuarNegeris->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($kerjasamaLuarNegeris)
                ->addColumn('aksi', function ($kerjasamaLuarNegeris) {
                    return view('admin.pages.kerja-sama.luar-negeri.tombol-aksi', compact('kerjasamaLuarNegeris'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.kerja-sama.luar-negeri.index', [
            'icon' => 'search',
            'title' => 'Kerja Sama Luar Negeri',
            'subtitle' => 'Daftar Kerja Sama Luar Negeri',
            'active' => 'kerja-sama-luar-negeri',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.kerja-sama.luar-negeri.form', [
            'icon' => 'plus',
            'title' => 'Kerja Sama Luar Negeri',
            'subtitle' => 'Tambah Data Kerja Sama Luar Negeri',
            'active' => 'kerja-sama-luar-negeri',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'instansi' => 'required',
            'jenis_kegiatan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_berakhir' => 'required',
        ], [
            'instansi.required' => 'Instansi harus diisi.',
            'jenis_kegiatan.required' => 'Jenis Kegiatan harus diisi.',
            'tanggal_mulai.required' => 'Tanggal Mulai harus diisi.',
            'tanggal_berakhir.required' => 'Tanggal Berakhir harus diisi.',
        ]);

        try { // jika sukses menambahkan data

            // simpan data
            KerjasamaLuarNegeri::create([
                'instansi' => $request->instansi,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'tgl_mulai' => $request->tanggal_mulai,
                'tgl_berakhir' => $request->tanggal_berakhir,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('kerjasamaLuarNegeri.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('kerjasamaLuarNegeri.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model KerjasamaLuarNegeri berdasarkan id
            $kerjasamaLuarNegeri = KerjasamaLuarNegeri::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.kerja-sama.luar-negeri.form', [
                'icon' => 'edit',
                'title' => 'Kerja Sama Luar Negeri',
                'subtitle' => 'Edit Kerja Sama Luar Negeri',
                'active' => 'kerja-sama-luar-negeri',
                'kerjasamaLuarNegeri' => $kerjasamaLuarNegeri,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kerjasamaLuarNegeri.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kerjasamaLuarNegeri.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'instansi' => 'required',
            'jenis_kegiatan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_berakhir' => 'required',
        ], [
            'instansi.required' => 'Instansi harus diisi.',
            'jenis_kegiatan.required' => 'Jenis Kegiatan harus diisi.',
            'tanggal_mulai.required' => 'Tanggal Mulai harus diisi.',
            'tanggal_berakhir.required' => 'Tanggal Berakhir harus diisi.',
        ]);

        try { // jika id ditemukan
            // ambil data dari model KerjasamaLuarNegeri berdasarkan id
            $kerjasamaLuarNegeri = KerjasamaLuarNegeri::findOrFail($id);

            $kerjasamaLuarNegeri->update([
                'instansi' => $request->instansi,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'tgl_mulai' => $request->tanggal_mulai,
                'tgl_berakhir' => $request->tanggal_berakhir,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('kerjasamaLuarNegeri.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kerjasamaLuarNegeri.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('kerjasamaLuarNegeri.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model KerjasamaLuarNegeri berdasarkan id
            $kerjasamaLuarNegeri = KerjasamaLuarNegeri::findOrFail($id);

            // hapus data dari table kerjasama_luar_negeris
            $kerjasamaLuarNegeri->delete();

            return redirect()->route('kerjasamaLuarNegeri.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kerjasamaLuarNegeri.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('kerjasamaLuarNegeri.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
