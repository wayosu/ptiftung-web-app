<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->memilikiperan('Superadmin') || Auth::user()->memilikiperan('Admin') || Auth::user()->memilikiperan('Kajur')) {
            // total dosen
            $totalDosen = \App\Models\Dosen::count();

            // total mahasiswa
            $totalMahasiswa = \App\Models\Mahasiswa::count();

            // total kegiatan mahasiswa
            $totalKegiatanMahasiswa = \App\Models\KegiatanMahasiswa::count();

            // total penelitian
            $totalPenelitian = \App\Models\Penelitian::count();

            // total pengabdian masyarakat
            $totalPengabdianMasyarakat = \App\Models\PengabdianMasyarakat::count();

            // total publikasi
            $totalPublikasi = \App\Models\Publikasi::count();

            // total kerja sama dalam negeri
            $totalKerjasamaDalamNegeri = \App\Models\KerjasamaDalamNegeri::count();

            // total kerjasama luar negeri
            $totalKerjasamaLuarNegeri = \App\Models\KerjasamaLuarNegeri::count();

            // total berita
            $totalBerita = \App\Models\Berita::count();

            // total agenda
            $totalAgenda = \App\Models\Agenda::count();

            return view('admin.pages.dashboard', [
                'icon' => 'activity',
                'title' => 'Dasbor',
                'subtitle' => 'Ikhtisar dasbor dan ringkasan informasi.',
                'active' => 'dasbor',
                'totalDosen' => $totalDosen ?? null,
                'totalMahasiswa' => $totalMahasiswa ?? null,
                'totalKegiatanMahasiswa' => $totalKegiatanMahasiswa ?? null,
                'totalPenelitian' => $totalPenelitian ?? null,
                'totalPengabdianMasyarakat' => $totalPengabdianMasyarakat ?? null,
                'totalPublikasi' => $totalPublikasi ?? null,
                'totalKerjasamaDalamNegeri' => $totalKerjasamaDalamNegeri ?? null,
                'totalKerjasamaLuarNegeri' => $totalKerjasamaLuarNegeri ?? null,
                'totalBerita' => $totalBerita ?? null,
                'totalAgenda' => $totalAgenda ?? null,
            ]);
        } else if (Auth::user()->memilikiperan('Kaprodi')) {
            // total dosen by program studi
            $totalDosen = \App\Models\Dosen::where('program_studi', Auth::user()->dosen->program_studi)->count();

            // total mahasiswa by program studi
            $totalMahasiswa = \App\Models\Mahasiswa::where('program_studi', Auth::user()->dosen->program_studi)->count();

            // total kegiatan mahasiswa by program studi
            $totalKegiatanMahasiswa = \App\Models\KegiatanMahasiswa::where('program_studi', Auth::user()->dosen->program_studi)->count();

            // total penelitian by dosen from program studi
            $totalPenelitian = \App\Models\Penelitian::whereHas('dosen', function ($query) {
                $query->where('program_studi', Auth::user()->dosen->program_studi);
            })->count();



            // total pengabdian masyarakat by dosen from program studi
            $totalPengabdianMasyarakat = \App\Models\PengabdianMasyarakat::whereHas('dosen', function ($query) {
                $query->where('program_studi', Auth::user()->dosen->program_studi);
            })->count();

            // total publikasi by dosen from program studi
            $totalPublikasi = \App\Models\Publikasi::whereHas('dosen', function ($query) {
                $query->where('program_studi', Auth::user()->dosen->program_studi);
            })->count();

            // total kerjasama dalam negeri by program studi
            $totalKerjasamaDalamNegeri = \App\Models\KerjasamaDalamNegeri::where('program_studi', Auth::user()->dosen->program_studi)->count();

            // total kerjasama luar negeri by program studi
            $totalKerjasamaLuarNegeri = \App\Models\KerjasamaLuarNegeri::where('program_studi', Auth::user()->dosen->program_studi)->count();

            // total berita by program studi
            $totalBerita = \App\Models\Berita::count();

            // total agenda by program studi by program studi
            $totalAgenda = \App\Models\Agenda::where('program_studi', Auth::user()->dosen->program_studi)->count();

            return view('admin.pages.dashboard', [
                'icon' => 'activity',
                'title' => 'Dasbor',
                'subtitle' => 'Ikhtisar dasbor dan ringkasan informasi.',
                'active' => 'dasbor',
                'totalDosen' => $totalDosen ?? null,
                'totalMahasiswa' => $totalMahasiswa ?? null,
                'totalKegiatanMahasiswa' => $totalKegiatanMahasiswa ?? null,
                'totalPenelitian' => $totalPenelitian ?? null,
                'totalPengabdianMasyarakat' => $totalPengabdianMasyarakat ?? null,
                'totalPublikasi' => $totalPublikasi ?? null,
                'totalKerjasamaDalamNegeri' => $totalKerjasamaDalamNegeri ?? null,
                'totalKerjasamaLuarNegeri' => $totalKerjasamaLuarNegeri ?? null,
                'totalBerita' => $totalBerita ?? null,
                'totalAgenda' => $totalAgenda ?? null,
            ]);
        } else if (Auth::user()->memilikiperan('Dosen')) {
            $program_studi = Auth::user()->dosen->program_studi;

            // total penelitian
            $totalPenelitian = \App\Models\Penelitian::where('dosen_id', Auth::user()->dosen->id)->count();

            // total pengabdian masyarakat
            $totalPengabdianMasyarakat = \App\Models\PengabdianMasyarakat::where('dosen_id', Auth::user()->dosen->id)->count();

            // total publikasi
            $totalPublikasi = \App\Models\Publikasi::where('dosen_id', Auth::user()->dosen->id)->count();

            // total berita
            $totalBerita = \App\Models\Berita::where('created_by', Auth::user()->id)->count();

            // total kegiatan yang diikut
            $totalKegiatan = \App\Models\Kegiatan::whereHas('dosen', function ($query) {
                $query->where('dosen_id', Auth::user()->dosen->id);
            })->pluck('kegiatan_mahasiswa_id')->unique()->count();

            // total mahasiswa yang didampingi
            $totalMahasiswa = \App\Models\Kegiatan::whereHas('dosen', function ($query) {
                $query->where('dosen_id', Auth::user()->dosen->id);
            })->pluck('mahasiswa_id')->unique()->count();

            // total lampiran yang disetujui
            $totalLampiranDisetujui = \App\Models\LampiranKegiatan::whereHas('kegiatan', function ($query) {
                $query->whereHas('dosen', function ($query) {
                    $query->where('dosen_id', Auth::user()->dosen->id);
                });
            })->where('status', 'disetujui')->count();

            // total lampiran yang ditolak
            $totalLampiranDitolak = \App\Models\LampiranKegiatan::whereHas('kegiatan', function ($query) {
                $query->whereHas('dosen', function ($query) {
                    $query->where('dosen_id', Auth::user()->dosen->id);
                });
            })->where('status', 'ditolak')->count();

            return view('admin.pages.dashboard', [
                'icon' => 'activity',
                'title' => 'Dasbor',
                'subtitle' => 'Ikhtisar dasbor dan ringkasan informasi.',
                'active' => 'dasbor',
                'program_studi' => $program_studi ?? null,
                'totalPenelitian' => $totalPenelitian ?? null,
                'totalPengabdianMasyarakat' => $totalPengabdianMasyarakat ?? null,
                'totalPublikasi' => $totalPublikasi ?? null,
                'totalBerita' => $totalBerita ?? null,
                'totalKegiatan' => $totalKegiatan ?? null,
                'totalMahasiswa' => $totalMahasiswa ?? null,
                'totalLampiranDisetujui' => $totalLampiranDisetujui ?? null,
                'totalLampiranDitolak' => $totalLampiranDitolak ?? null
            ]);
        } else if (Auth::user()->memilikiperan('Mahasiswa')) {
            $program_studi = Auth::user()->mahasiswa->program_studi;

            // total kegiatan yang diikut
            $totalKegiatan = \App\Models\Kegiatan::whereHas('mahasiswa', function ($query) {
                $query->where('mahasiswa_id', Auth::user()->mahasiswa->id);
            })->count();

            // total lampiran
            $totalLampiran = \App\Models\LampiranKegiatan::whereHas('kegiatan', function ($query) {
                $query->whereHas('mahasiswa', function ($query) {
                    $query->where('mahasiswa_id', Auth::user()->mahasiswa->id);
                });
            })->count();

            // total lampiran yang disetujui
            $totalLampiranDisetujui = \App\Models\LampiranKegiatan::whereHas('kegiatan', function ($query) {
                $query->whereHas('mahasiswa', function ($query) {
                    $query->where('mahasiswa_id', Auth::user()->mahasiswa->id);
                });
            })->where('status', 'disetujui')->count();

            // total lampiran yang ditolak
            $totalLampiranDitolak = \App\Models\LampiranKegiatan::whereHas('kegiatan', function ($query) {
                $query->whereHas('mahasiswa', function ($query) {
                    $query->where('mahasiswa_id', Auth::user()->mahasiswa->id);
                });
            })->where('status', 'ditolak')->count();

            return view('admin.pages.dashboard', [
                'icon' => 'activity',
                'title' => 'Dasbor',
                'subtitle' => 'Ikhtisar dasbor dan ringkasan informasi.',
                'active' => 'dasbor',
                'program_studi' => $program_studi ?? null,
                'totalKegiatan' => $totalKegiatan ?? null,
                'totalLampiran' => $totalLampiran ?? null,
                'totalLampiranDisetujui' => $totalLampiranDisetujui ?? null,
                'totalLampiranDitolak' => $totalLampiranDitolak ?? null,
            ]);
        } else {
            abort(404);
        }
    }
}
