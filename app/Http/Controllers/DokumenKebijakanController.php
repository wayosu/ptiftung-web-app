<?php

namespace App\Http\Controllers;

use App\Models\DokumenKebijakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class DokumenKebijakanController extends Controller
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
                $dokumenKebijakans = DokumenKebijakan::orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi() || $this->checkDosen()) {
                $dokumenKebijakans = DokumenKebijakan::where('program_studi', Auth::user()->dosen->program_studi)->orderBy('created_at', 'desc')->get();
            }

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
        // data kategoris dalam array
        $kategoris = [
            'Pendidikan' => 'Pendidikan',
            'Penelitian' => 'Penelitian',
            'Pengabdian' => 'Pengabdian',
            'Kemahasiswaan' => 'Kemahasiswaan',
            'Kerja Sama' => 'Kerja Sama',
            'Tata Kelola' => 'Tata Kelola',
        ];

        // Validasi data yang dikirim
        $validationRules = [
            'kategori' => 'required|in:' . implode(',', array_keys($kategoris)),
            'keterangan' => 'required',
            'dokumen' => 'sometimes|nullable|mimes:doc,docx,pdf,xls,xlsx|max:4096',
            'link_dokumen' => 'nullable|url',
        ];
    
        $validationMessages = [
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
            return redirect()->route('dokumenKebijakan.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

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
                'program_studi' => $program_studi,
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan,
                'dokumen' => $nameFile,
                'link_dokumen' => $linkDokumen,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('dokumenKebijakan.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('dokumenKebijakan.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model DokumenKebijakan berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $dokumenKebijakan = DokumenKebijakan::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $dokumenKebijakan = DokumenKebijakan::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            } else {
                return redirect()->route('dokumenKebijakan.index')->with('error', 'Halaman bermasalah!. Anda tidak mempunyai hak akses.');
            }

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
        // data kategoris dalam array
        $kategoris = [
            'Pendidikan' => 'Pendidikan',
            'Penelitian' => 'Penelitian',
            'Pengabdian' => 'Pengabdian',
            'Kemahasiswaan' => 'Kemahasiswaan',
            'Kerja Sama' => 'Kerja Sama',
            'Tata Kelola' => 'Tata Kelola',
        ];

        // Validasi data yang dikirim
        $validationRules = [
            'kategori' => 'required|in:' . implode(',', array_keys($kategoris)),
            'keterangan' => 'required',
            'dokumen' => 'sometimes|nullable|mimes:doc,docx,pdf,xls,xlsx|max:4096',
            'link_dokumen' => 'nullable|url',
        ];
    
        $validationMessages = [
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
            return redirect()->route('dokumenKebijakan.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika data valid
            // ambil data dari model DokumenKebijakan berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $dokumenKebijakan = DokumenKebijakan::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $dokumenKebijakan = DokumenKebijakan::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // Perbarui data dengan nilai yang dikirim dari formulir
            $dokumenKebijakan->program_studi = $program_studi;
            $dokumenKebijakan->kategori = $request->kategori;
            $dokumenKebijakan->keterangan = $request->keterangan;
            $dokumenKebijakan->updated_by = Auth::user()->id;

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
            if ($this->checkSuperadminAdminKajur()) {
                $dokumenKebijakan = DokumenKebijakan::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $dokumenKebijakan = DokumenKebijakan::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

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
