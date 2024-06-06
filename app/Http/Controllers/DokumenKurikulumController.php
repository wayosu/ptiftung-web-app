<?php

namespace App\Http\Controllers;

use App\Models\DokumenKurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DokumenKurikulumController extends Controller
{
    private function checkSuperadminAdminKajur()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Superadmin') || $userAuth->memilikiperan('Admin') || $userAuth->	memilikiperan('Kajur'); 
    }

    private function checkKaprodi()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Kaprodi');
    }
    
    public function index()
    {
        // ambil data
        if ($this->checkSuperadminAdminKajur()) {
            $dokumenKurikulums = DokumenKurikulum::with('createdBy')
                ->orderBy('created_at', 'desc')->get();
        } else if ($this->checkKaprodi()) {
            $dokumenKurikulums = DokumenKurikulum::with('createdBy')
                ->where('program_studi', Auth::user()->dosen->program_studi)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // tampilkan halaman
        return view('admin.pages.akademik.dokumen-kurikulum.index', [
            'icon' => 'fa-solid fa-graduation-cap',
            'title' => 'Dokumen Kurikulum',
            'subtitle' => 'Daftar Dokumen Kurikulum',
            'active' => 'dokumen-kurikulum',
            'dokumenKurikulums' => $dokumenKurikulums
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'keterangan' => 'required|string|max:255',
            'link_gdrive' => 'required',
        ];
    
        $validationMessages = [
            'keterangan.required' => 'Keterangan harus diisi!',
            'keterangan.string' => 'Keterangan harus berupa string!',
            'keterangan.max' => 'Keterangan maksimal 255 karakter!',
            'link_gdrive.required' => 'Dokumen harus diisi!',
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
            return redirect()->route('dokumenKurikulum.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses menambahkan data
            DokumenKurikulum::create([
                'keterangan' => $request->keterangan,
                'link_gdrive' => $request->link_gdrive,
                'active' => 0,
                'program_studi' => $program_studi,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('dokumenKurikulum.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('dokumenKurikulum.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function updateStatus(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'id' => 'required|exists:dokumen_kurikulums,id',
            'status' => 'required|boolean',
        ]);

        try {
            // Perbarui status dokumen kurikulum
            $dokumenKurikulum = DokumenKurikulum::findOrFail($request->id);
            $dokumenKurikulum->active = $request->status;
            $dokumenKurikulum->save();

            return response()->json(['message' => 'Status berhasil diperbarui', 'status' => $dokumenKurikulum->active], 200);
        } catch (\Exception $e) {
            // Tangani error jika terjadi kesalahan saat menyimpan
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui status'], 500);
        }
    }

    public function destroy($id)
    {
        try { // jika sukses hapus data
            // ambil data dari model DokumenKurikulum berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $dokumenKurikulum = DokumenKurikulum::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $dokumenKurikulum = DokumenKurikulum::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // hapus data
            $dokumenKurikulum->delete();

            return redirect()->route('dokumenKurikulum.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('dokumenKurikulum.index')->with('error', 'Data gagal dihapus. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal hapus data
            return redirect()->route('dokumenKurikulum.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
