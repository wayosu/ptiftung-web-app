<?php

namespace App\Http\Controllers;

use App\Models\DokumenLainnya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class DokumenLainnyaController extends Controller
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
                $dokumenLainnyas = DokumenLainnya::orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi() || $this->checkDosen()) {
                $dokumenLainnyas = DokumenLainnya::where('program_studi', Auth::user()->dosen->program_studi)->orderBy('created_at', 'desc')->get();
            }

            // transformasi data ke bentuk array
            $dokumenLainnyas = $dokumenLainnyas->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($dokumenLainnyas)
                ->addColumn('aksi', function ($dokumenLainnyas) {
                    return view('admin.pages.repositori.dokumen-lainnya.tombol-aksi', compact('dokumenLainnyas'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.repositori.dokumen-lainnya.index', [
            'icon' => 'archive',
            'title' => 'Dokumen Lainnya',
            'subtitle' => 'Daftar Dokumen Lainnya',
            'active' => 'dokumen-lainnya',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.repositori.dokumen-lainnya.form', [
            'icon' => 'plus',
            'title' => 'Dokumen Lainnya',
            'subtitle' => 'Tambah Dokumen Lainnya',
            'active' => 'dokumen-lainnya',
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'keterangan' => 'required',
            'dokumen' => 'sometimes|nullable|mimes:doc,docx,pdf,xls,xlsx|max:4096',
            'link_dokumen' => 'nullable|url',
        ];
    
        $validationMessages = [
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
            return redirect()->route('dokumenLainnya.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika data valid
            // Jika menggunakan file dokumen
            if ($request->hasFile('dokumen') && empty($request->link_dokumen)) {
                // Namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('dokumen')->getClientOriginalExtension();

                // Simpan file ke storage/penyimpanan
                $storePath = 'repositori/dokumen-lainnya';
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
                return redirect()->route('dokumenLainnya.create')->with('error', 'Isi salah satu dari Dokumen atau Link Dokumen');
            }

            // Simpan data
            DokumenLainnya::create([
                'program_studi' => $program_studi,
                'keterangan' => $request->keterangan,
                'dokumen' => $nameFile,
                'link_dokumen' => $linkDokumen,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('dokumenLainnya.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('dokumenLainnya.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model DokumenLainnya berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $dokumenLainnya = DokumenLainnya::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $dokumenLainnya = DokumenLainnya::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            } else {
                return redirect()->route('dokumenLainnya.index')->with('error', 'Halaman bermasalah!. Anda tidak mempunyai hak akses.');
            }

            // tampilkan halaman
            return view('admin.pages.repositori.dokumen-lainnya.form', [
                'icon' => 'edit',
                'title' => 'Dokumen Lainnya',
                'subtitle' => 'Edit Dokumen Lainnya',
                'active' => 'dokumen-lainnya',
                'dokumenLainnya' => $dokumenLainnya,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dokumenLainnya.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('dokumenLainnya.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'keterangan' => 'required',
            'dokumen' => 'sometimes|nullable|mimes:doc,docx,pdf,xls,xlsx|max:4096',
            'link_dokumen' => 'nullable|url',
        ];
    
        $validationMessages = [
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
            return redirect()->route('dokumenLainnya.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika data valid
            // ambil data dari model DokumenLainnya berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $dokumenLainnya = DokumenLainnya::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $dokumenLainnya = DokumenLainnya::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // Perbarui data dengan nilai yang dikirim dari formulir
            $dokumenLainnya->program_studi = $program_studi;
            $dokumenLainnya->keterangan = $request->keterangan;
            $dokumenLainnya->updated_by = Auth::user()->id;

            // Perbarui dokumen jika ada
            if ($request->hasFile('dokumen')) {
                // cek apakah ada file yang lama
                if (Storage::exists('repositori/dokumen-lainnya/' . $dokumenLainnya->dokumen)) {
                    // hapus file
                    Storage::delete('repositori/dokumen-lainnya/' . $dokumenLainnya->dokumen);
                }
                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('dokumen')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'repositori/dokumen-lainnya';
                $request->file('dokumen')->storeAs($storePath, $nameFile);

                // Simpan nama dokumen ke dalam model
                $dokumenLainnya->dokumen = $nameFile;

                // Kosongkan link_dokumen
                $dokumenLainnya->link_dokumen = null;
            }

            // Perbarui link dokumen jika ada
            if ($request->filled('link_dokumen')) {
                $dokumenLainnya->dokumen = null;
                $dokumenLainnya->link_dokumen = $request->link_dokumen;
            }

            // Simpan perubahan
            $dokumenLainnya->save();

            // Redirect ke halaman yang sesuai dengan pesan sukses
            return redirect()->route('dokumenLainnya.index')->with('success', 'Data berhasil diperbarui!');
        }  catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dokumenLainnya.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal mengupdate data
            return redirect()->route('dokumenLainnya.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model DokumenLainnya berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $dokumenLainnya = DokumenLainnya::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $dokumenLainnya = DokumenLainnya::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // hapus file dari storage/penyimpanan
            if ($dokumenLainnya->dokumen) {
                if (Storage::exists('repositori/dokumen-lainnya/' . $dokumenLainnya->dokumen)) {
                    Storage::delete('repositori/dokumen-lainnya/' . $dokumenLainnya->dokumen);
                }
            }

            // hapus data dari table Dokumen Lainnya
            $dokumenLainnya->delete();

            return redirect()->route('dokumenLainnya.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dokumenLainnya.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('dokumenLainnya.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
