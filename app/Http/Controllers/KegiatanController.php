<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kegiatan;
use App\Models\KegiatanMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class KegiatanController extends Controller
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
    
    public function index()
    {
        try {
            // query KegiatanMahasiswa
            $query = KegiatanMahasiswa::select(['id', 'nama_kegiatan', 'deskripsi', 'slug', 'program_studi']);

            if ($this->checkSuperadminAdminKajur()) {
                // ambil data berdasarkan program_studi === SISTEM INFORMASI
                $kmsiQuery = clone $query;
                $kmsi = $kmsiQuery->where('program_studi', 'SISTEM INFORMASI')->get();
    
                // ambil data berdasarkan program_studi === PEND. TEKNOLOGI INFORMASI
                $kmptiQuery = clone $query;
                $kmpti = $kmptiQuery->where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->get();
    
                // ambil total mahasiswa per kegiatan dari program studi SISTEM INFORMASI
                $kmsiTotals = $kmsi->mapWithKeys(function ($kegiatan) {
                    return [$kegiatan->id => Kegiatan::where('kegiatan_mahasiswa_id', $kegiatan->id)->count()];
                });
    
                // ambil total mahasiswa per kegiatan dari program studi PEND. TEKNOLOGI INFORMASI
                $kmptiTotals = $kmpti->mapWithKeys(function ($kegiatan) {
                    return [$kegiatan->id => Kegiatan::where('kegiatan_mahasiswa_id', $kegiatan->id)->count()];
                });

                // tampilkan halaman
                return view('admin.pages.layanan-administrasi.kegiatan.index', [
                    'icon' => 'fa-solid fa-people-line',
                    'title' => 'Kegiatan',
                    'subtitle' => 'Daftar Kegiatan',
                    'active' => 'kegiatan',
                    'kmsi' => $kmsi,
                    'kmpti' => $kmpti,
                    'kmsiTotals' => $kmsiTotals,
                    'kmptiTotals' => $kmptiTotals
                ]);
            } else if ($this->checkKaprodi()) {
                // ambil data berdasarkan program_studi === PEND. TEKNOLOGI INFORMASI
                $kmQuery = clone $query;
                $km = $kmQuery->where('program_studi', auth()->user()->dosen->program_studi)->get();
                // ambil total mahasiswa per kegiatan dari program studi PEND. TEKNOLOGI INFORMASI
                $kmTotals = $km->mapWithKeys(function ($kegiatan) {
                    return [$kegiatan->id => Kegiatan::where('kegiatan_mahasiswa_id', $kegiatan->id)->count()];
                });

                // tampilkan halaman
                return view('admin.pages.layanan-administrasi.kegiatan.index', [
                    'icon' => 'fa-solid fa-people-line',
                    'title' => 'Kegiatan',
                    'subtitle' => 'Daftar Kegiatan',
                    'active' => 'kegiatan',
                    'km' => $km,
                    'kmTotals' => $kmTotals
                ]);
            }

            // tampilkan halaman
            return view('admin.pages.layanan-administrasi.kegiatan.index', [
                'icon' => 'fa-solid fa-people-line',
                'title' => 'Kegiatan',
                'subtitle' => 'Daftar Kegiatan',
                'active' => 'kegiatan',
                'kmsi' => $kmsi,
                'kmpti' => $kmpti,
                'kmsiTotals' => $kmsiTotals,
                'kmptiTotals' => $kmptiTotals
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return abort(404, 'Halaman tidak ditemukan');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return abort(404, 'Halaman sedang bermasalah. Data tidak ditemukan!');
        }
    }

    public function show($kegiatan_mahasiswa_id)
    {
        try {
            // cari data berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $kegiatanMahasiswa = KegiatanMahasiswa::findOrFail($kegiatan_mahasiswa_id);
            } else if ($this->checkKaprodi()) {
                $kegiatanMahasiswa = KegiatanMahasiswa::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($kegiatan_mahasiswa_id);
            }

            // ambil data berdasarkan kegiatan_mahasiswa_id
            $kegiatans = Kegiatan::with(['dosen', 'mahasiswa'])->where('kegiatan_mahasiswa_id', $kegiatan_mahasiswa_id)->get();

            // ambil data dosen berdasarkan program_studi
            $dosens = Dosen::with('user')
                ->where('program_studi', $kegiatanMahasiswa->program_studi)
                ->get();
            
            // ambil beberapa data dosen berdasarkan program_studi
            $dosenList = $dosens->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->user->name
                ];
            });

            // ordering data dosen by name -> asc
            $dosenList = $dosenList->sortBy('name')->toArray();
            
            // ambil daftar mahasiswa_id yang sudah terdaftar dalam kegiatan
            $mahasiswaIdsInKegiatan = $kegiatans->pluck('mahasiswa_id');

            // ambil data Mahasiswa berdasarkan program_studi dan tidak termasuk dalam kegiatan
            $mahasiswas = Mahasiswa::with('user')
                ->where('program_studi', $kegiatanMahasiswa->program_studi)
                ->whereNotIn('id', $mahasiswaIdsInKegiatan)
                ->get();

            // ambil daftar angkatan yang unik
            $angkatanList = $mahasiswas->pluck('angkatan')->unique()->sortDesc();

            // tampilkan halaman
            return view('admin.pages.layanan-administrasi.kegiatan.detail', [
                'icon' => 'file-text',
                'title' => 'Kegiatan ' . $kegiatanMahasiswa->nama_kegiatan,
                'subtitle' => 'Daftar mahasiswa Program Studi ' . $kegiatanMahasiswa->program_studi . ' yang mengikuti ' . $kegiatanMahasiswa->nama_kegiatan,
                'active' => 'kegiatan',
                'kegiatanMahasiswaId' => $kegiatanMahasiswa->id,
                'kegiatans' => $kegiatans,
                'dosenList' => $dosenList,
                'angkatanList' => $angkatanList
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return abort(404, 'Halaman tidak ditemukan');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            // \Log::error('Error fetching data for kegiatan_mahasiswa_id: ' . $kegiatan_mahasiswa_id, ['exception' => $e]);
            return abort(500, 'Halaman sedang bermasalah. Data tidak ditemukan!');
        }
    }

    public function getMahasiswaByAngkatan(Request $request)
    {
        $angkatan = $request->input('angkatan');
        $kegiatanMahasiswaId = $request->input('kegiatan_mahasiswa_id');
    
        // Ambil data kegiatan mahasiswa untuk mendapatkan program_studi
        $kegiatanMahasiswa = KegiatanMahasiswa::findOrFail($kegiatanMahasiswaId);

        // Ambil daftar mahasiswa_id yang sudah terdaftar dalam kegiatan
        $mahasiswaIdsInKegiatan = Kegiatan::where('kegiatan_mahasiswa_id', $kegiatanMahasiswaId)
            ->pluck('mahasiswa_id');
    
        // Ambil data Mahasiswa berdasarkan angkatan dan tidak termasuk dalam kegiatan
        $mahasiswas = Mahasiswa::with('user')
            ->where('program_studi', $kegiatanMahasiswa->program_studi)
            ->where('angkatan', $angkatan)
            ->whereNotIn('id', $mahasiswaIdsInKegiatan)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nim' => $item->nim,
                    'name' => $item->user->name,
                ];
            });
    
        return response()->json($mahasiswas);
    }

    public function dataTables(Request $request, $kegiatan_mahasiswa_id)
    {
        if ($request->ajax()) {
            $kegiatans = Kegiatan::with(['dosen.user', 'mahasiswa.user'])->where('kegiatan_mahasiswa_id', $kegiatan_mahasiswa_id)->get();

            // transformasi data ke bentuk array
            $kegiatans = $kegiatans->transform(function ($item) {
                return $item;
            })->all();
            
            // tampilkan data dalam format DataTables
            return DataTables::of($kegiatans)
                ->addColumn('aksi', function ($kegiatans) {
                    return view('admin.pages.layanan-administrasi.kegiatan.tombol-aksi', compact('kegiatans'));
                })
                ->make(true);
        }
    }

    public function store(Request $request, $kegiatan_mahasiswa_id)
    {
        // validasi data yang dikirim
        $request->validate([
            'angkatan' => 'required',
            'dosen_id' => 'required',
            'mahasiswa_id' => 'required',
        ],[
            'angkatan.required' => 'Angkatan harus dipilih',
            'dosen_id.required' => 'Dosen harus dipilih',
            'mahasiswa_id.required' => 'Mahasiswa harus dipilih',
        ]);

        try {
            // cek apakah kegiatan_mahasiswa_id ditemukan
            $kegiatanMahasiswa = KegiatanMahasiswa::findOrFail($kegiatan_mahasiswa_id);

            // cek apakah sudah ada data dengan kegiatan_mahasiswa_id dan mahasiswa_id yang sama
            $kegiatans = Kegiatan::where('kegiatan_mahasiswa_id', $kegiatanMahasiswa->id)
                ->where('mahasiswa_id', $request->mahasiswa_id)
                ->get();

            if (count($kegiatans) > 0) {
                return redirect()->back()->with('error', 'Peserta gagal ditambahkan. Data sudah ada!');
            }

            // simpan data kegiatan
            $kegiatan = new Kegiatan();
            $kegiatan->kegiatan_mahasiswa_id = $kegiatan_mahasiswa_id;
            $kegiatan->dosen_id = $request->dosen_id;
            $kegiatan->mahasiswa_id = $request->mahasiswa_id;
            $kegiatan->save();

            return redirect()->route('kegiatan.show', $kegiatan_mahasiswa_id)->with('success', 'Peserta berhasil ditambahkan!');
        }  catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kegiatan.show', $kegiatan_mahasiswa_id)->with('error', 'Peserta gagal ditambahkan. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kegiatan.show', $kegiatan_mahasiswa_id)->with('error', 'Data bermasalah. Peserta gagal ditambahkan!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model Kegiatan berdasarkan id
            $kegiatan = Kegiatan::findOrFail($id);

            // hapus data Kegiatan
            $kegiatan->delete(); 

            return redirect()->route('kegiatan.show', $kegiatan->kegiatan_mahasiswa_id)->with('success', 'Peserta berhasil dihapus!');
        }  catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('kegiatan.show', $kegiatan->kegiatan_mahasiswa_id)->with('error', 'Peserta gagal dihapus. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kegiatan.show', $kegiatan->kegiatan_mahasiswa_id)->with('error', 'Data bermasalah. Peserta gagal dihapus!');
        }
    }
}
