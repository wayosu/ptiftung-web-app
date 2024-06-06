<?php

namespace App\Http\Controllers;

use App\Models\PrestasiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PrestasiMahasiswaController extends Controller
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
                $prestasiMahasiswas = PrestasiMahasiswa::orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi() || $this->checkDosen()) {
                $prestasiMahasiswas = PrestasiMahasiswa::where('program_studi', Auth::user()->dosen->program_studi)->orderBy('created_at', 'desc')->get();
            }

            // transformasi data ke bentuk array
            $prestasiMahasiswas = $prestasiMahasiswas->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($prestasiMahasiswas)
                ->addColumn('aksi', function ($prestasiMahasiswas) {
                    return view('admin.pages.mahasiswa-dan-alumni.prestasi-mahasiswa.tombol-aksi', compact('prestasiMahasiswas'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.prestasi-mahasiswa.index', [
            'icon' => 'fas fa-users-rays',
            'title' => 'Prestasi Mahasiswa',
            'subtitle' => 'Daftar Prestasi Mahasiswa',
            'active' => 'prestasi-mahasiswa',
        ]);
    }

    public function create()
    {
        // data tingkat dalam array
        $tingkats = [
            'Internasional' => 'Internasional',
            'Nasional' => 'Nasional',
            'Daerah/Provinsi' => 'Daerah/Provinsi',
            'Wilayah' => 'Wilayah',
            'Regional' => 'Regional',
            'UNG' => 'UNG',
        ];

        // tampilkan halaman
        return view('admin.pages.mahasiswa-dan-alumni.prestasi-mahasiswa.form', [
            'icon' => 'plus',
            'title' => 'Prestasi Mahasiswa',
            'subtitle' => 'Tambah Prestasi Mahasiswa',
            'active' => 'prestasi-mahasiswa',
            'tingkats' => $tingkats,
        ]);
    }

    public function store(Request $request)
    {
        // data tingkat dalam array
        $tingkats = [
            'Internasional' => 'Internasional',
            'Nasional' => 'Nasional',
            'Daerah/Provinsi' => 'Daerah/Provinsi',
            'Wilayah' => 'Wilayah',
            'Regional' => 'Regional',
            'UNG' => 'UNG',
        ];

        // Validasi data yang dikirim
        $validationRules = [
            'nama_mahasiswa' => 'required',
            'predikat' => 'required',
            'tingkat' => 'required|in:' . implode(',', array_keys($tingkats)),
            'tahun' => 'required',
            'kegiatan' => 'required',
        ];
    
        $validationMessages = [
            'nama_mahasiswa.required' => 'Nama Mahasiswa harus diisi.',
            'predikat.required' => 'Predikat harus diisi.',
            'tingkat.required' => 'Tingkat harus dipilih.',
            'tingkat.in' => 'Tingkat yang dipilih tidak valid.',
            'tahun.required' => 'Tahun harus diisi.',
            'kegiatan.required' => 'Kegiatan harus diisi.',
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
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses menambahkan data
            // simpan data
            PrestasiMahasiswa::create([
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'program_studi' => $program_studi,
                'predikat' => $request->predikat,
                'tingkat' => $request->tingkat,
                'tahun' => $request->tahun,
                'kegiatan' => $request->kegiatan,
                'created_by' => auth()->user()->id,
            ]);

            return redirect()->route('prestasiMahasiswa.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data gagal ditambahkan.');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model PrestasiMahasiswa berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $prestasiMahasiswa = PrestasiMahasiswa::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $prestasiMahasiswa = PrestasiMahasiswa::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            } else {
                return redirect()->route('prestasiMahasiswa.index')->with('error', 'Halaman bermasalah!. Anda tidak mempunyai hak akses.');
            }

            // data tingkat dalam array
            $tingkats = [
                'Internasional' => 'Internasional',
                'Nasional' => 'Nasional',
                'Daerah/Provinsi' => 'Daerah/Provinsi',
                'Wilayah' => 'Wilayah',
                'Regional' => 'Regional',
                'UNG' => 'UNG',
            ];

            // tampilkan halaman
            return view('admin.pages.mahasiswa-dan-alumni.prestasi-mahasiswa.form', [
                'icon' => 'edit',
                'title' => 'Prestasi Mahasiswa',
                'subtitle' => 'Edit Prestasi Mahasiswa',
                'active' => 'prestasi-mahasiswa',
                'prestasiMahasiswa' => $prestasiMahasiswa,
                'tingkats' => $tingkats,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // data tingkat dalam array
        $tingkats = [
            'Internasional' => 'Internasional',
            'Nasional' => 'Nasional',
            'Daerah/Provinsi' => 'Daerah/Provinsi',
            'Wilayah' => 'Wilayah',
            'Regional' => 'Regional',
            'UNG' => 'UNG',
        ];

        // Validasi data yang dikirim
        $validationRules = [
            'nama_mahasiswa' => 'required',
            'predikat' => 'required',
            'tingkat' => 'required|in:' . implode(',', array_keys($tingkats)),
            'kegiatan' => 'required',
        ];
    
        $validationMessages = [
            'nama_mahasiswa.required' => 'Nama Mahasiswa harus diisi.',
            'predikat.required' => 'Predikat harus diisi.',
            'tingkat.required' => 'Tingkat harus dipilih.',
            'tingkat.in' => 'Tingkat yang dipilih tidak valid.',
            'kegiatan.required' => 'Kegiatan harus diisi.',
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
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika id ditemukan
            // ambil data dari model PrestasiMahasiswa berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $prestasiMahasiswa = PrestasiMahasiswa::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $prestasiMahasiswa = PrestasiMahasiswa::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            $tahun = $request->tahun;
            $tahun_lama = $request->tahun_lama;

            if ($tahun == null) {
                $prestasiMahasiswa->update([
                    'nama_mahasiswa' => $request->nama_mahasiswa,
                    'predikat' => $request->predikat,
                    'tingkat' => $request->tingkat,
                    'tahun' => $tahun_lama,
                    'kegiatan' => $request->kegiatan,
                    'updated_by' => auth()->user()->id,
                ]);
            } else {
                $prestasiMahasiswa->update([
                    'nama_mahasiswa' => $request->nama_mahasiswa,
                    'predikat' => $request->predikat,
                    'tingkat' => $request->tingkat,
                    'tahun' => $request->tahun,
                    'kegiatan' => $request->kegiatan,
                    'updated_by' => auth()->user()->id,
                ]);
            }

            return redirect()->route('prestasiMahasiswa.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model PrestasiMahasiswa berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $prestasiMahasiswa = PrestasiMahasiswa::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $prestasiMahasiswa = PrestasiMahasiswa::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // hapus data dari table prestasi_mahasiswas
            $prestasiMahasiswa->delete();

            return redirect()->route('prestasiMahasiswa.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('prestasiMahasiswa.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
