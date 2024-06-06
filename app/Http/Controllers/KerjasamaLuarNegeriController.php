<?php

namespace App\Http\Controllers;

use App\Models\KerjasamaLuarNegeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class KerjasamaLuarNegeriController extends Controller
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
                $kerjasamaLuarNegeris = KerjasamaLuarNegeri::orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi() || $this->checkDosen()) {
                $kerjasamaLuarNegeris = KerjasamaLuarNegeri::where('program_studi', Auth::user()->dosen->program_studi)->orderBy('created_at', 'desc')->get();
            }

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
            'icon' => 'far fa-handshake',
            'title' => 'Kerja Sama Luar Negeri',
            'subtitle' => 'Daftar Kerja Sama Luar Negeri',
            'active' => 'kerja-sama-luar-negeri',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.kerja-sama.luar-negeri.form', [
            'icon' => 'fas fa-plus',
            'title' => 'Kerja Sama Luar Negeri',
            'subtitle' => 'Tambah Data Kerja Sama Luar Negeri',
            'active' => 'kerja-sama-luar-negeri',
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'instansi' => 'required',
            'jenis_kegiatan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_berakhir' => 'required',
        ];
    
        $validationMessages = [
            'instansi.required' => 'Instansi harus diisi!',
            'jenis_kegiatan.required' => 'Jenis Kegiatan harus diisi!',
            'tanggal_mulai.required' => 'Tanggal Mulai harus diisi!',
            'tanggal_berakhir.required' => 'Tanggal Berakhir harus diisi!',
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
            return redirect()->route('kerjasamaLuarNegeri.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses menambahkan data

            // simpan data
            KerjasamaLuarNegeri::create([
                'instansi' => $request->instansi,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'tgl_mulai' => $request->tanggal_mulai,
                'tgl_berakhir' => $request->tanggal_berakhir,
                'program_studi' => $program_studi,
                'created_by' => Auth::user()->id,
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
            if ($this->checkSuperadminAdminKajur()) {
                $kerjasamaLuarNegeri = KerjasamaLuarNegeri::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $kerjasamaLuarNegeri = KerjasamaLuarNegeri::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            } else {
                return redirect()->route('kerjasamaLuarNegeri.index')->with('error', 'Halaman bermasalah!. Anda tidak mempunyai hak akses.');
            }

            // tampilkan halaman
            return view('admin.pages.kerja-sama.luar-negeri.form', [
                'icon' => 'fas fa-pen-to-square',
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
        // Validasi data yang dikirim
        $validationRules = [
            'instansi' => 'required',
            'jenis_kegiatan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_berakhir' => 'required',
        ];
    
        $validationMessages = [
            'instansi.required' => 'Instansi harus diisi!',
            'jenis_kegiatan.required' => 'Jenis Kegiatan harus diisi!',
            'tanggal_mulai.required' => 'Tanggal Mulai harus diisi!',
            'tanggal_berakhir.required' => 'Tanggal Berakhir harus diisi!',
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
            return redirect()->route('kerjasamaLuarNegeri.index')->with('error', 'Data gagal diperbarui!. Anda tidak mempunyai hak akses.');
        }

        try { // jika id ditemukan
            // ambil data dari model KerjasamaLuarNegeri berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $kerjasamaLuarNegeri = KerjasamaLuarNegeri::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $kerjasamaLuarNegeri = KerjasamaLuarNegeri::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            $kerjasamaLuarNegeri->update([
                'instansi' => $request->instansi,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'tgl_mulai' => $request->tanggal_mulai,
                'tgl_berakhir' => $request->tanggal_berakhir,
                'program_studi' => $program_studi,
                'updated_by' => Auth::user()->id,
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
            if ($this->checkSuperadminAdminKajur()) {
                $kerjasamaLuarNegeri = KerjasamaLuarNegeri::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $kerjasamaLuarNegeri = KerjasamaLuarNegeri::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

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
