<?php

namespace App\Http\Controllers;

use App\Models\CapaianPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CapaianPembelajaranController extends Controller
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
                $capaianPembelajarans = CapaianPembelajaran::with('createdBy')->orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi()) {
                $capaianPembelajarans = CapaianPembelajaran::with('createdBy')
                    ->where('program_studi', Auth::user()->dosen->program_studi)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            // transformasi data ke bentuk array
            $capaianPembelajarans = $capaianPembelajarans->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($capaianPembelajarans)
                ->addColumn('aksi', function ($capaianPembelajarans) {
                    return view('admin.pages.akademik.capaian-pembelajaran.tombol-aksi', compact('capaianPembelajarans'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.akademik.capaian-pembelajaran.index', [
            'icon' => 'fa-solid fa-graduation-cap',
            'title' => 'Capaian Pembelajaran',
            'subtitle' => 'Daftar Capaian Pembelajaran',
            'active' => 'capaian-pembelajaran',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.akademik.capaian-pembelajaran.form', [
            'icon' => 'plus',
            'title' => 'Capaian Pembelajaran',
            'subtitle' => 'Tambah Capaian Pembelajaran',
            'active' => 'capaian-pembelajaran',
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'capaian_pembelajaran' => 'required',
        ];
    
        $validationMessages = [
            'capaian_pembelajaran.required' => 'Capaian Pembelajaran harus diisi!',
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
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses menambahkan data
            CapaianPembelajaran::create([
                'capaian_pembelajaran' => $request->capaian_pembelajaran,
                'program_studi' => $program_studi,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('capaianPembelajaran.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika sukses mengambil data
            // ambil data dari model CapaianPembelajaran berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $capaianPembelajaran = CapaianPembelajaran::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $capaianPembelajaran = CapaianPembelajaran::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // tampilkan halaman
            return view('admin.pages.akademik.capaian-pembelajaran.form', [
                'icon' => 'edit',
                'title' => 'Capaian Pembelajaran',
                'subtitle' => 'Edit Capaian Pembelajaran',
                'active' => 'capaian-pembelajaran',
                'capaianPembelajaran' => $capaianPembelajaran,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'capaian_pembelajaran' => 'required',
        ];
    
        $validationMessages = [
            'capaian_pembelajaran.required' => 'Capaian Pembelajaran harus diisi!',
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
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal diperbarui!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses update data
            // ambil data dari model CapaianPembelajaran berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $capaianPembelajaran = CapaianPembelajaran::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $capaianPembelajaran = CapaianPembelajaran::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            $capaianPembelajaran->update([
                'capaian_pembelajaran' => $request->capaian_pembelajaran,
                'program_studi' => $program_studi,
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->route('capaianPembelajaran.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal diperbarui. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal update data
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika sukses hapus data
            // ambil data dari model CapaianPembelajaran berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $capaianPembelajaran = CapaianPembelajaran::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $capaianPembelajaran = CapaianPembelajaran::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // hapus data
            $capaianPembelajaran->delete();

            return redirect()->route('capaianPembelajaran.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal dihapus. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal hapus data
            return redirect()->route('capaianPembelajaran.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
