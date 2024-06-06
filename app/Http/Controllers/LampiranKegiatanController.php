<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanMahasiswa;
use App\Models\LampiranKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LampiranKegiatanController extends Controller
{
    public function index($kegiatan_mahasiswa_slug)
    {
        try {
            $kegiatanMahasiswa = KegiatanMahasiswa::where('slug', $kegiatan_mahasiswa_slug)->first();

            // ambil data dari model Kegiatan
            if (auth()->user()->memilikiPeran('Mahasiswa')) {
                $kegiatan = Kegiatan::with('dosen.user')->where('kegiatan_mahasiswa_id', $kegiatanMahasiswa->id)
                    ->where('mahasiswa_id', auth()->user()->mahasiswa->id)
                    ->firstOrFail();

                return view('admin.pages.layanan-administrasi.lampiran.index', [
                    'icon' => 'file-text',
                    'title' => 'Kegiatan ' . $kegiatanMahasiswa->nama_kegiatan,
                    'subtitle' => 'Daftar Lampiran Kegiatan ' . $kegiatanMahasiswa->nama_kegiatan . ' dari Program Studi ' . $kegiatanMahasiswa->program_studi,
                    'active' => $kegiatanMahasiswa->slug,
                    'kegiatan' => $kegiatan
                ]);
            } else if (auth()->user()->memilikiperan('Superadmin') || auth()->user()->memilikiperan('Admin') || auth()->user()->memilikiPeran('Kajur') || auth()->user()->memilikiperan('Kaprodi') || auth()->user()->memilikiperan('Dosen')) {
                return view('admin.pages.layanan-administrasi.lampiran.index', [
                    'icon' => 'file-text',
                    'title' => 'Kegiatan ' . $kegiatanMahasiswa->nama_kegiatan,
                    'subtitle' => 'Daftar Lampiran Kegiatan ' . $kegiatanMahasiswa->nama_kegiatan . ' dari Program Studi ' . $kegiatanMahasiswa->program_studi,
                    'active' => $kegiatanMahasiswa->slug,
                ]);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) {
            abort(404, 'Halaman tidak ditemukan');
        } catch (\Exception $e) {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    public function dataTables(Request $request, $kegiatan_id)
    {
        if ($request->ajax()) {
            $lampirans = LampiranKegiatan::with('kegiatan')->where('kegiatan_id', $kegiatan_id)->get();

            // transformasi data ke bentuk array
            $lampirans = $lampirans->transform(function ($item) {
                return $item;
            })->all();
            
            // tampilkan data dalam format DataTables
            return DataTables::of($lampirans)
                ->addColumn('aksi', function ($lampirans) {
                    return view('admin.pages.layanan-administrasi.lampiran.tombol-aksi', compact('lampirans'));
                })
                ->make(true);
        }
    }

    public function getDataTables(Request $request, $kegiatan_mahasiswa_slug)
    {
        if ($request->ajax()) {
            $kegiatanMahasiswa = KegiatanMahasiswa::where('slug', $kegiatan_mahasiswa_slug)->first();

            if (auth()->user()->memilikiPeran('Dosen')) {
                $kegiatans = Kegiatan::with(['mahasiswa.user', 'lampiranKegiatans'])
                    ->where('kegiatan_mahasiswa_id', $kegiatanMahasiswa->id)
                    ->where('dosen_id', auth()->user()->dosen->id)
                    ->get();
            } else {
                $kegiatans = Kegiatan::with(['mahasiswa.user', 'dosen.user', 'lampiranKegiatans'])
                    ->where('kegiatan_mahasiswa_id', $kegiatanMahasiswa->id)
                    ->get();
            }

            // transformasi data ke bentuk array
            $kegiatans = $kegiatans->transform(function ($item) {
                // Cek apakah semua lampiran sudah direview untuk setiap kegiatan
                $item->allReviewed = $item->lampiranKegiatans->every(function ($lampiran) {
                    return $lampiran->status !== 'belum direview';
                });
                return $item;
            })->all();
            
            // tampilkan data dalam format DataTables
            return DataTables::of($kegiatans)
            ->addColumn('aksi', function ($kegiatans) {
                return view('admin.pages.layanan-administrasi.lampiran.tombol-aksi', [
                    'kegiatans' => $kegiatans,
                    'allReviewed' => $kegiatans['allReviewed']
                ]);
            })
            ->make(true);
        }
    }

    public function store(Request $request, $kegiatan_id)
    {
        $request->validate([
            'keterangan_lampiran' => 'required',
            'file_lampiran' => 'required|array',
            'file_lampiran.*' => 'mimes:pdf,docx,doc,jpeg,jpg,png',
        ], [
            'keterangan_lampiran.required' => 'Keterangan lampiran harus diisi!',
            'file_lampiran.required' => 'File lampiran harus diisi!',
            'file_lampiran.array' => 'File lampiran harus berupa array!',
            'file_lampiran.*.mimes' => 'File lampiran harus berupa pdf, docx, doc, jpeg, jpg, png!',
        ]);

        try {
            $kegiatan = Kegiatan::findOrFail($kegiatan_id);
            $kegiatanMahasiswa = KegiatanMahasiswa::findOrFail($kegiatan->kegiatan_mahasiswa_id);
    
            // Membuat entri LampiranKegiatan
            $lampiranKegiatan = LampiranKegiatan::create([
                'kegiatan_id' => $kegiatan->id,
                'keterangan_lampiran' => $request->get('keterangan_lampiran'),
            ]);
    
            if ($request->hasFile('file_lampiran')) {
                foreach ($request->file('file_lampiran') as $file) {
                    $nameFile = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                    $storePath = 'lampiran/kegiatan/' . $kegiatanMahasiswa->slug;
    
                    $file->storeAs($storePath, $nameFile);
    
                    // Menyimpan data FileLampiranKegiatan untuk setiap file yang di-upload
                    $lampiranKegiatan->fileLampiranKegiatans()->create([
                        'file_path' => $storePath,
                        'file_name' => $nameFile,
                    ]);
                }
    
                return redirect()->back()->with('success', 'Lampiran kegiatan berhasil ditambahkan!');
            } else {
                return redirect()->back()->with('error', 'File lampiran harus diisi!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi masalah! Lampiran kegiatan gagal ditambahkan.');
        }
    }

    public function detail($id)
    {
        $lampiranKegiatan = LampiranKegiatan::with(['kegiatan', 'fileLampiranKegiatans'])->findOrFail($id);

        $kegiatanMahasiswa = $lampiranKegiatan->kegiatan->kegiatanMahasiswa;
    
        return view('admin.pages.layanan-administrasi.lampiran.detail', [
            'icon' => 'file-text',
            'title' => 'Detail Lampiran Kegiatan',
            'subtitle' => 'Detail Lampiran Kegiatan ' . $kegiatanMahasiswa->nama_kegiatan,
            'active' => $kegiatanMahasiswa->slug,
            'kegiatanMahasiswa' => $kegiatanMahasiswa,
            'lampiranKegiatan' => $lampiranKegiatan,
        ]);
    }

    public function review($kegiatan_id)
    {
        try {
            $kegiatan = Kegiatan::findOrFail($kegiatan_id);

            $lampiranKegiatans = $kegiatan->lampiranKegiatans;

            return view('admin.pages.layanan-administrasi.lampiran.review', [
                'icon' => 'file-text',
                'title' => 'Review Lampiran Kegiatan',
                'subtitle' => 'Review Lampiran Kegiatan ' . $kegiatan->kegiatanMahasiswa->nama_kegiatan,
                'active' => $kegiatan->kegiatanMahasiswa->slug,
                'kegiatan' => $kegiatan,
                'lampiranKegiatans' => $lampiranKegiatans
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi masalah! Kegiatan tidak ditemukan!');
        }
    }

    public function setujui(Request $request, $lampiran_id)
    {
        $validator = Validator::make($request->all(), [
            'catatan_dosen' => 'required',
        ], [
            'catatan_dosen.required' => 'Catatan revisi tidak boleh kosong!',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400); // 400 Bad Request
        }
        
        try {
            $lampiranKegiatan = LampiranKegiatan::findOrFail($lampiran_id);

            $lampiranKegiatan->update([
                'status' => 'disetujui',
                'catatan_dosen' => $request->get('catatan_dosen'),
            ]);

            return response()->json(['success' => 'Lampiran disetujui.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi masalah! Lampiran gagal disetujui.']);
        }
    }

    public function tolak(Request $request, $lampiran_id)
    {
        $validator = Validator::make($request->all(), [
            'catatan_dosen' => 'required',
        ], [
            'catatan_dosen.required' => 'Catatan revisi tidak boleh kosong!',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400); // 400 Bad Request
        }

        try {
            $lampiranKegiatan = LampiranKegiatan::findOrFail($lampiran_id);

            $lampiranKegiatan->update([
                'status' => 'ditolak',
                'catatan_dosen' => $request->get('catatan_dosen'),
            ]);

            return response()->json(['success' => 'Lampiran ditolak.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi masalah! Lampiran gagal ditolak.']);
        }
    }

    public function batal($lampiran_id)
    {
        try {
            $lampiranKegiatan = LampiranKegiatan::findOrFail($lampiran_id);

            $lampiranKegiatan->update([
                'status' => 'belum direview',
                'catatan_dosen' => null,
            ]);

            return response()->json(['success' => 'Status lampiran dikembalikan ke belum direview.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi masalah! status lampiran gagal dikembalikan.']);
        }
    }
    

    public function destroy($id)
    {
        try {
            // Ambil entri LampiranKegiatan berdasarkan id
            $lampiranKegiatan = LampiranKegiatan::findOrFail($id);

            // Hapus semua FileLampiranKegiatan yang tersimpan di storage
            foreach ($lampiranKegiatan->fileLampiranKegiatans as $fileLampiranKegiatan) {
                if (Storage::exists($fileLampiranKegiatan->file_path . '/' . $fileLampiranKegiatan->file_name)) {
                    Storage::delete($fileLampiranKegiatan->file_path . '/' . $fileLampiranKegiatan->file_name);
                }

                $fileLampiranKegiatan->delete();
            }

            // Hapus entri LampiranKegiatan
            $lampiranKegiatan->delete();

            return redirect()->back()->with('success', 'Lampiran kegiatan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi masalah! Lampiran kegiatan gagal dihapus.');
        }
    }
}
