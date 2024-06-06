<?php

namespace App\Http\Controllers;

use App\Models\DataDukungAkreditasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class DataDukungAkreditasiController extends Controller
{
    private function checkSuperadminAdminKajur()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Superadmin') || $userAuth->memilikiperan('Admin') || $userAuth->memilikiperan('Kajur'); 
    }

    private function checkKaprodi()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Kaprodi');
    }

    private function checkDosen()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Dosen');
    }
    
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            if ($this->checkSuperadminAdminKajur()) {
                $ddas = DataDukungAkreditasi::orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi() || $this->checkDosen()) {
                $ddas = DataDukungAkreditasi::where('program_studi', Auth::user()->dosen->program_studi)->orderBy('created_at', 'desc')->get();
            }

            // transformasi data ke bentuk array
            $ddas = $ddas->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($ddas)
                ->addColumn('aksi', function ($ddas) {
                    return view('admin.pages.repositori.data-dukung-akreditasi.tombol-aksi', compact('ddas'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.repositori.data-dukung-akreditasi.index', [
            'icon' => 'archive',
            'title' => 'Data Dukung Akreditasi 2023',
            'subtitle' => 'Daftar Data Dukung Akreditasi 2023',
            'active' => 'data-dukung-akreditasi',
        ]);
    }

    public function create()
    {
        // data kategoris dalam array
        $kategoris = [
            'UPPS' => 'UPPS',
            'Program Studi' => 'Program Studi',
            'Dokumentasi Video' => 'Dokumentasi Video',
        ];

        // tampilkan halaman
        return view('admin.pages.repositori.data-dukung-akreditasi.form', [
            'icon' => 'plus',
            'title' => 'Data Dukung Akreditasi 2023',
            'subtitle' => 'Tambah Data Dukung Akreditasi 2023',
            'active' => 'data-dukung-akreditasi',
            'kategoris' => $kategoris,
        ]);
    }

    public function store(Request $request)
    {
        // data kategoris dalam array
        $kategoris = [
            'UPPS' => 'UPPS',
            'Program Studi' => 'Program Studi',
            'Dokumentasi Video' => 'Dokumentasi Video',
        ];

        // Validasi data yang dikirim
        $validationRules = [
            'nomor_butir' => 'required',
            'kategori' => 'required|in:' . implode(',', array_keys($kategoris)),
            'keterangan' => 'required',
            'dokumen' => 'sometimes|nullable|mimes:doc,docx,pdf,xls,xlsx|max:4096',
            'link_dokumen' => 'nullable|url',
        ];
    
        $validationMessages = [
            'nomor_butir.required' => 'Nomor Butir harus diisi!',
            'kategori.required' => 'Kategori harus dipilih!',
            'kategori.in' => 'Kategori tidak valid!',
            'keterangan.required' => 'Keterangan harus diisi!',
            'dokumen.mimes' => 'Dokumen harus berupa doc, docx, pdf, xls, xlsx!',
            'dokumen.max' => 'Dokumen tidak boleh lebih dari 4 MB!',
            'link_dokumen.url' => 'Link dokumen harus berupa URL yang valid',
        ];
    
        if ($this->checkSuperadminAdminKajur()) {
            $validationRules['program_studi'] = 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI';
            $validationMessages['program_studi.required'] = 'Program Studi harus dipilih!';
            $validationMessages['program_studi.in'] = 'Program Studi tidak valid!';
        }
    
        $request->validate($validationRules, $validationMessages);

        if ($this->checkSuperadminAdminKajur()) {
            $program_studi = $request->program_studi;
        } else if ($this->checkKaprodi()) {
            $program_studi = Auth::user()->dosen->program_studi;
        } else {
            return redirect()->route('dataDukungAkreditasi.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika data valid
            // Jika menggunakan file dokumen
            if ($request->hasFile('dokumen') && empty($request->link_dokumen)) {
                // Namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('dokumen')->getClientOriginalExtension();

                // Simpan file ke storage/penyimpanan
                $storePath = 'repositori/data-dukung-akreditasi';
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
                return redirect()->route('dataDukungAkreditasi.create')->with('error', 'Isi salah satu dari Dokumen atau Link Dokumen');
            }

            // Simpan data
            DataDukungAkreditasi::create([
                'program_studi' => $program_studi,
                'nomor_butir' => $request->nomor_butir,
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan,
                'dokumen' => $nameFile,
                'link_dokumen' => $linkDokumen,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('dataDukungAkreditasi.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('dataDukungAkreditasi.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model DataDukungAkreditasi berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $dda = DataDukungAkreditasi::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $dda = DataDukungAkreditasi::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            } else {
                return redirect()->route('dataDukungAkreditasi.index')->with('error', 'Halaman bermasalah!. Anda tidak mempunyai hak akses.');
            }

            // data kategoris dalam array
            $kategoris = [
                'UPPS' => 'UPPS',
                'Program Studi' => 'Program Studi',
                'Dokumentasi Video' => 'Dokumentasi Video',
            ];

            // tampilkan halaman
            return view('admin.pages.repositori.data-dukung-akreditasi.form', [
                'icon' => 'edit',
                'title' => 'Data Dukung Akreditasi 2023',
                'subtitle' => 'Edit Data Dukung Akreditasi 2023',
                'active' => 'data-dukung-akreditasi',
                'dda' => $dda,
                'kategoris' => $kategoris,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dataDukungAkreditasi.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('dataDukungAkreditasi.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // data kategoris dalam array
        $kategoris = [
            'UPPS' => 'UPPS',
            'Program Studi' => 'Program Studi',
            'Dokumentasi Video' => 'Dokumentasi Video',
        ];

        // Validasi data yang dikirim
        $validationRules = [
            'nomor_butir' => 'required',
            'kategori' => 'required|in:' . implode(',', array_keys($kategoris)),
            'keterangan' => 'required',
            'dokumen' => 'sometimes|nullable|mimes:doc,docx,pdf,xls,xlsx|max:4096',
            'link_dokumen' => 'nullable|url',
        ];
    
        $validationMessages = [
            'nomor_butir.required' => 'Nomor Butir harus diisi!',
            'kategori.required' => 'Kategori harus dipilih!',
            'kategori.in' => 'Kategori tidak valid!',
            'keterangan.required' => 'Keterangan harus diisi!',
            'dokumen.mimes' => 'Dokumen harus berupa doc, docx, pdf, xls, xlsx!',
            'dokumen.max' => 'Dokumen tidak boleh lebih dari 4 MB!',
            'link_dokumen.url' => 'Link dokumen harus berupa URL yang valid',
        ];
    
        if ($this->checkSuperadminAdminKajur()) {
            $validationRules['program_studi'] = 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI';
            $validationMessages['program_studi.required'] = 'Program Studi harus dipilih!';
            $validationMessages['program_studi.in'] = 'Program Studi tidak valid!';
        }
    
        $request->validate($validationRules, $validationMessages);

        if ($this->checkSuperadminAdminKajur()) {
            $program_studi = $request->program_studi;
        } else if ($this->checkKaprodi()) {
            $program_studi = Auth::user()->dosen->program_studi;
        } else {
            return redirect()->route('dataDukungAkreditasi.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika data valid
            // ambil data dari model DataDukungAkreditasi berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $dda = DataDukungAkreditasi::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $dda = DataDukungAkreditasi::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // Perbarui data dengan nilai yang dikirim dari formulir
            $dda->program_studi = $program_studi;
            $dda->nomor_butir = $request->nomor_butir;
            $dda->kategori = $request->kategori;
            $dda->keterangan = $request->keterangan;
            $dda->updated_by = Auth::user()->id;

            // Perbarui dokumen jika ada
            if ($request->hasFile('dokumen')) {
                // cek apakah ada file yang lama
                if (Storage::exists('repositori/data-dukung-akreditasi/' . $dda->dokumen)) {
                    // hapus file
                    Storage::delete('repositori/data-dukung-akreditasi/' . $dda->dokumen);
                }
                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('dokumen')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'repositori/data-dukung-akreditasi';
                $request->file('dokumen')->storeAs($storePath, $nameFile);

                // Simpan nama dokumen ke dalam model
                $dda->dokumen = $nameFile;

                // Kosongkan link_dokumen
                $dda->link_dokumen = null;
            }

            // Perbarui link dokumen jika ada
            if ($request->filled('link_dokumen')) {
                $dda->dokumen = null;
                $dda->link_dokumen = $request->link_dokumen;
            }

            // Simpan perubahan
            $dda->save();

            // Redirect ke halaman yang sesuai dengan pesan sukses
            return redirect()->route('dataDukungAkreditasi.index')->with('success', 'Data berhasil diperbarui!');
        }  catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dataDukungAkreditasi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal mengupdate data
            return redirect()->route('dataDukungAkreditasi.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model DataDukungAkreditasi berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $dda = DataDukungAkreditasi::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $dda = DataDukungAkreditasi::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // hapus file dari storage/penyimpanan
            if ($dda->dokumen) {
                if (Storage::exists('repositori/data-dukung-akreditasi/' . $dda->dokumen)) {
                    Storage::delete('repositori/data-dukung-akreditasi/' . $dda->dokumen);
                }
            }

            // hapus data dari table Data Dukung Akreditasi
            $dda->delete();

            return redirect()->route('dataDukungAkreditasi.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dataDukungAkreditasi.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('dataDukungAkreditasi.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
