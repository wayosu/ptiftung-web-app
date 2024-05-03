<?php

namespace App\Http\Controllers;

use App\Models\DokumenKebijakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class DokumenKebijakanController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $dokumenKebijakans = DokumenKebijakan::orderBy('created_at', 'desc')->get();

            // transformasi data ke bentuk array
            $dokumenKebijakans = $dokumenKebijakans->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($dokumenKebijakans)
                ->addColumn('aksi', function ($dokumenKebijakans) {
                    return view('admin.pages.repositori.dokumen-kebijakan.tombol-aksi', compact('dokumenKebijakans'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.repositori.dokumen-kebijakan.index', [
            'icon' => 'archive',
            'title' => 'Dokumen Kebijakan',
            'subtitle' => 'Daftar Dokumen Kebijakan',
            'active' => 'dokumen-kebijakan',
        ]);
    }

    public function create()
    {
        // data kategoris dalam array
        $kategoris = [
            'Pendidikan' => 'Pendidikan',
            'Penelitian' => 'Penelitian',
            'Pengabdian' => 'Pengabdian',
            'Kemahasiswaan' => 'Kemahasiswaan',
            'Kerja Sama' => 'Kerja Sama',
            'Tata Kelola' => 'Tata Kelola',
        ];

        // tampilkan halaman
        return view('admin.pages.repositori.dokumen-kebijakan.form', [
            'icon' => 'plus',
            'title' => 'Dokumen Kebijakan',
            'subtitle' => 'Tambah Dokumen Kebijakan',
            'active' => 'dokumen-kebijakan',
            'kategoris' => $kategoris,
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'kategori' => 'required',
            'keterangan' => 'required',
            'dokumen' => 'sometimes|nullable|mimes:doc,docx,pdf,xls,xlsx|max:4096',
            'link_dokumen' => 'nullable|url',
        ], [
            'kategori.required' => 'Kategori harus dipilih!',
            'keterangan.required' => 'Keterangan harus diisi!',
            'dokumen.mimes' => 'Dokumen harus berupa doc, docx, pdf, xls, xlsx!',
            'dokumen.max' => 'Dokumen tidak boleh lebih dari 4 MB!',
            'link_dokumen.url' => 'Link dokumen harus berupa URL yang valid',
        ]);

        try { // jika data valid
            // Jika menggunakan file dokumen
            if ($request->hasFile('dokumen') && empty($request->link_dokumen)) {
                // Namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('dokumen')->getClientOriginalExtension();

                // Simpan file ke storage/penyimpanan
                $storePath = 'repositori/dokumen-kebijakan';
                $request->file('dokumen')->storeAs($storePath, $nameFile);

                // Kosongkan link dokumen
                $linkDokumen = null;
            } elseif (!empty($request->link_dokumen) && empty($request->dokumen)) {
                // Kosongkan nama file jika menggunakan link dokumen
                $nameFile = null;

                // Gunakan link dokumen
                $linkDokumen = $request->link_dokumen;
            } else {
                // Kembalikan response jika tidak ada file atau link dokumen yang valid
                return redirect()->route('dokumenKebijakan.create')->with('error', 'Isi salah satu dari Dokumen atau Link Dokumen');
            }

            // Simpan data
            DokumenKebijakan::create([
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan,
                'dokumen' => $nameFile,
                'link_dokumen' => $linkDokumen,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('dokumenKebijakan.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('dokumenKebijakan.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // data kategoris dalam array
            $kategoris = [
                'Pendidikan' => 'Pendidikan',
                'Penelitian' => 'Penelitian',
                'Pengabdian' => 'Pengabdian',
                'Kemahasiswaan' => 'Kemahasiswaan',
                'Kerja Sama' => 'Kerja Sama',
                'Tata Kelola' => 'Tata Kelola',
            ];

            // ambil data dari model DokumenKebijakan berdasarkan id
            $dokumenKebijakan = DokumenKebijakan::findOrFail($id);

            // tampilkan halaman
            return view('admin.pages.repositori.dokumen-kebijakan.form', [
                'icon' => 'edit',
                'title' => 'Dokumen Kebijakan',
                'subtitle' => 'Edit Dokumen Kebijakan',
                'active' => 'dokumen-kebijakan',
                'dokumenKebijakan' => $dokumenKebijakan,
                'kategoris' => $kategoris,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dokumenKebijakan.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('dokumenKebijakan.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'kategori' => 'required',
            'keterangan' => 'required',
            'dokumen' => 'sometimes|nullable|mimes:doc,docx,pdf,xls,xlsx|max:4096',
            'link_dokumen' => 'nullable|url',
        ], [
            'kategori.required' => 'Kategori harus dipilih!',
            'keterangan.required' => 'Keterangan harus diisi!',
            'dokumen.mimes' => 'Dokumen harus berupa doc, docx, pdf, xls, xlsx!',
            'dokumen.max' => 'Dokumen tidak boleh lebih dari 4 MB!',
            'link_dokumen.url' => 'Link dokumen harus berupa URL yang valid',
        ]);

        try { // jika data valid
            // Ambil data dari model DokumenKebijakan berdasarkan id
            $dokumenKebijakan = DokumenKebijakan::findOrFail($id);

            // Perbarui data dengan nilai yang dikirim dari formulir
            $dokumenKebijakan->kategori = $request->kategori;
            $dokumenKebijakan->keterangan = $request->keterangan;
            $dokumenKebijakan->updated_by = auth()->user()->id;

            // Perbarui dokumen jika ada
            if ($request->hasFile('dokumen')) {
                // cek apakah ada file yang lama
                if (Storage::exists('repositori/dokumen-kebijakan/' . $dokumenKebijakan->dokumen)) {
                    // hapus file
                    Storage::delete('repositori/dokumen-kebijakan/' . $dokumenKebijakan->dokumen);
                }
                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('dokumen')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'repositori/dokumen-kebijakan';
                $request->file('dokumen')->storeAs($storePath, $nameFile);

                // Simpan nama dokumen ke dalam model
                $dokumenKebijakan->dokumen = $nameFile;

                // Kosongkan link_dokumen
                $dokumenKebijakan->link_dokumen = null;
            }

            // Perbarui link dokumen jika ada
            if ($request->filled('link_dokumen')) {
                $dokumenKebijakan->dokumen = null;
                $dokumenKebijakan->link_dokumen = $request->link_dokumen;
            }

            // Simpan perubahan
            $dokumenKebijakan->save();

            // Redirect ke halaman yang sesuai dengan pesan sukses
            return redirect()->route('dokumenKebijakan.index')->with('success', 'Data berhasil diperbarui!');
        }  catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dokumenKebijakan.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal mengupdate data
            return redirect()->route('dokumenKebijakan.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model DokumenKebijakan berdasarkan id
            $dokumenKebijakan = DokumenKebijakan::findOrFail($id);

            // hapus file dari storage/penyimpanan
            if ($dokumenKebijakan->dokumen) {
                if (Storage::exists('repositori/dokumen-kebijakan/' . $dokumenKebijakan->dokumen)) {
                    Storage::delete('repositori/dokumen-kebijakan/' . $dokumenKebijakan->dokumen);
                }
            }

            // hapus data dari table Dokumen Kebijakan
            $dokumenKebijakan->delete();

            return redirect()->route('dokumenKebijakan.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dokumenKebijakan.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('dokumenKebijakan.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
