<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class KurikulumController extends Controller
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
    
    public function index(Request $request)
    {
        
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            if ($this->checkSuperadminAdminKajur()) {
                $kurikulum = Kurikulum::orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi()) {
                $kurikulum = Kurikulum::where('program_studi', Auth::user()->dosen->program_studi)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            // transformasi data ke bentuk array
            $kurikulum = $kurikulum->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($kurikulum)
                ->addColumn('aksi', function ($kurikulum) {
                    return view('admin.pages.akademik.kurikulum.tombol-aksi', compact('kurikulum'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.akademik.kurikulum.index', [
            'icon' => 'fa-solid fa-graduation-cap',
            'title' => 'Kurikulum',
            'subtitle' => 'Daftar Kurikulum',
            'active' => 'kurikulum',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.akademik.kurikulum.form', [
            'icon' => 'plus',
            'title' => 'Kurikulum',
            'subtitle' => 'Tambah Kurikulum',
            'active' => 'kurikulum',
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'kode_mk' => 'required',
            'nama_mk' => 'required',
            'sks' => 'required',
            'sifat' => 'required',
            'semester' => 'required',
        ];
    
        $validationMessages = [
            'kode_mk.required' => 'Kode Mata Kuliah harus diisi!',
            'nama_mk.required' => 'Nama Mata Kuliah harus diisi!',
            'sks.required' => 'SKS harus diisi!',
            'sifat.required' => 'Sifat harus dipilih!',
            'semester.required' => 'Semester harus dipilih!',
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
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses menambahkan data
            Kurikulum::create([
                'kode_mk' => $request->kode_mk,
                'nama_mk' => $request->nama_mk,
                'sks' => $request->sks,
                'sifat' => $request->sifat,
                'semester' => $request->semester,
                'prasyarat' => $request->prasyarat,
                'link_gdrive' => $request->link_gdrive,
                'program_studi' => $program_studi,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('kurikulum.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika sukses mengambil data
            // ambil data dari model Kurikulum berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $kurikulum = Kurikulum::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $kurikulum = Kurikulum::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // tampilkan halaman
            return view('admin.pages.akademik.kurikulum.form', [
                'icon' => 'edit',
                'title' => 'Kurikulum',
                'subtitle' => 'Edit Kurikulum',
                'active' => 'kurikulum',
                'kurikulum' => $kurikulum,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kurikulum.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kurikulum.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'kode_mk' => 'required',
            'nama_mk' => 'required',
            'sks' => 'required',
            'sifat' => 'required',
            'semester' => 'required',
        ];
    
        $validationMessages = [
            'kode_mk.required' => 'Kode Mata Kuliah harus diisi!',
            'nama_mk.required' => 'Nama Mata Kuliah harus diisi!',
            'sks.required' => 'SKS harus diisi!',
            'sifat.required' => 'Sifat harus dipilih!',
            'semester.required' => 'Semester harus dipilih!',
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
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal diperbarui!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses update data
            // ambil data dari model Kurikulum berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $kurikulum = Kurikulum::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $kurikulum = Kurikulum::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            $kurikulum->update([
                'kode_mk' => $request->kode_mk,
                'nama_mk' => $request->nama_mk,
                'sks' => $request->sks,
                'sifat' => $request->sifat,
                'semester' => $request->semester,
                'prasyarat' => $request->prasyarat,
                'link_gdrive' => $request->link_gdrive,
                'program_studi' => $program_studi,
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->route('kurikulum.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal diperbarui. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal update data
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika sukses hapus data
            // ambil data dari model Kurikulum berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $kurikulum = Kurikulum::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $kurikulum = Kurikulum::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // hapus data
            $kurikulum->delete();

            return redirect()->route('kurikulum.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal dihapus. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal hapus data
            return redirect()->route('kurikulum.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
