<?php

namespace App\Http\Controllers;

use App\Models\KerjasamaDalamNegeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class KerjasamaDalamNegeriController extends Controller
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
            if ($this->checkSuperadminAdminKajur()) {
                $kerjasamaDalamNegeris = KerjasamaDalamNegeri::orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi() || $this->checkDosen()) {
                $kerjasamaDalamNegeris = KerjasamaDalamNegeri::where('program_studi', Auth::user()->dosen->program_studi)->orderBy('created_at', 'desc')->get();
            }

            // transformasi data ke bentuk array
            $kerjasamaDalamNegeris = $kerjasamaDalamNegeris->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($kerjasamaDalamNegeris)
                ->addColumn('aksi', function ($kerjasamaDalamNegeris) {
                    return view('admin.pages.kerja-sama.dalam-negeri.tombol-aksi', compact('kerjasamaDalamNegeris'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.kerja-sama.dalam-negeri.index', [
            'icon' => 'far fa-handshake',
            'title' => 'Kerja Sama Dalam Negeri',
            'subtitle' => 'Daftar Kerja Sama Dalam Negeri',
            'active' => 'kerja-sama-dalam-negeri',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.kerja-sama.dalam-negeri.form', [
            'icon' => 'fas fa-plus',
            'title' => 'Kerja Sama Dalam Negeri',
            'subtitle' => 'Tambah Data Kerja Sama Dalam Negeri',
            'active' => 'kerja-sama-dalam-negeri',
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
            return redirect()->route('kerjasamaDalamNegeri.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses menambahkan data

            // simpan data
            KerjasamaDalamNegeri::create([
                'instansi' => $request->instansi,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'tgl_mulai' => $request->tanggal_mulai,
                'tgl_berakhir' => $request->tanggal_berakhir,
                'program_studi' => $program_studi,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->route('kerjasamaDalamNegeri.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('kerjasamaDalamNegeri.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model KerjasamaLuarNegeri berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $kerjasamaDalamNegeri = KerjasamaDalamNegeri::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $kerjasamaDalamNegeri = KerjasamaDalamNegeri::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            } else {
                return redirect()->route('kerjasamaDalamNegeri.index')->with('error', 'Halaman bermasalah!. Anda tidak mempunyai hak akses.');
            }

            // tampilkan halaman
            return view('admin.pages.kerja-sama.dalam-negeri.form', [
                'icon' => 'fas fa-pen-to-square',
                'title' => 'Kerja Sama Dalam Negeri',
                'subtitle' => 'Edit Kerja Sama Dalam Negeri',
                'active' => 'kerja-sama-dalam-negeri',
                'kerjasamaDalamNegeri' => $kerjasamaDalamNegeri,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kerjasamaDalamNegeri.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kerjasamaDalamNegeri.index')->with('error', 'Halaman sedang bermasalah!');
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
            return redirect()->route('kerjasamaDalamNegeri.index')->with('error', 'Data gagal diperbarui!. Anda tidak mempunyai hak akses.');
        }

        try { // jika id ditemukan
            // ambil data dari model KerjasamaDalamNegeri berdasarkan id
            $kerjasamaDalamNegeri = KerjasamaDalamNegeri::findOrFail($id);

            $kerjasamaDalamNegeri->update([
                'instansi' => $request->instansi,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'tgl_mulai' => $request->tanggal_mulai,
                'tgl_berakhir' => $request->tanggal_berakhir,
                'program_studi' => $program_studi,
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->route('kerjasamaDalamNegeri.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kerjasamaDalamNegeri.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('kerjasamaDalamNegeri.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model KerjasamaDalamNegeri berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $kerjasamaDalamNegeri = KerjasamaDalamNegeri::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $kerjasamaDalamNegeri = KerjasamaDalamNegeri::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // hapus data dari table kerjasama_dalam_negeris
            $kerjasamaDalamNegeri->delete();

            return redirect()->route('kerjasamaDalamNegeri.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kerjasamaDalamNegeri.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('kerjasamaDalamNegeri.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
