<?php

namespace App\Http\Controllers\FrontPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function sejarah()
    {
        // ambil sejarah dari profil program studi PEND. TEKNOLOGI INFORMASI
        $sejarah = \App\Models\ProfilProgramStudi::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->firstOrFail('sejarah')->sejarah;

        return view('pages.profil.sejarah', [
            'title' => 'Sejarah',
            'active' => 'sejarah',
            'sejarah' => $sejarah
        ]);
    }

    public function visiTujuanStrategi()
    {
        // ambil visi tujuan dan strategi dari profil program studi PEND. TEKNOLOGI INFORMASI
        $visiTujuanStrategi = \App\Models\ProfilProgramStudi::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->firstOrFail(['visi_keilmuan', 'tujuan', 'strategi']);
        
        return view('pages.profil.visi-tujuan-strategi', [
            'title' => 'Visi, Tujuan, dan Strategi',
            'active' => 'visi-tujuan-strategi',
            'visiTujuanStrategi' => $visiTujuanStrategi
        ]);
    }

    public function strukturOrganisasi()
    {
        // ambil struktur organisasi dari profil program studi PEND. TEKNOLOGI INFORMASI
        $strukturOrganisasi = \App\Models\ProfilProgramStudi::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->firstOrFail('struktur_organisasi')->struktur_organisasi;

        return view('pages.profil.struktur-organisasi', [
            'title' => 'Struktur Organisasi',
            'active' => 'struktur-organisasi',
            'strukturOrganisasi' => $strukturOrganisasi
        ]);
    }

    public function dosen()
    {
        // Ambil dosen by program studi PEND. TEKNOLOGI INFORMASI
        $dosens = \App\Models\Dosen::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')
            ->with(['user:id,name,foto']) // Memuat relasi user dengan kolom yang diperlukan
            ->paginate(8); // Mengambil 5 data per halaman
        
        // Gabungkan data dosen dan data pengguna terkait ke dalam satu array
        $dosenArray = $dosens->map(function ($dosen) {
            return [
                'nip' => $dosen->nip,
                'slug' => $dosen->slug,
                'jafa' => $dosen->jafa,
                'name' => $dosen->user->name,
                'foto' => $dosen->user->foto,
            ];
        })->toArray();

        return view('pages.profil.dosen.index', [
            'title' => 'Dosen',
            'active' => 'dosen',
            'dosens' => $dosenArray,
            'pagination' => $dosens // Mengirim objek pagination ke view
        ]);
    }

    public function detailDosen($slug)
    {
        // ambil dosen berdasarkan slug
        $dosen = \App\Models\Dosen::where('slug', $slug)
            ->where('program_studi', 'PEND. TEKNOLOGI INFORMASI')
            ->with(['user:id,email,name,foto', 'pendidikans', 'bidangKepakarans', 'penelitians', 'pengabdianMasyarakats', 'publikasis'])
            ->firstOrFail();

        // Gabungkan data dosen dan data pengguna terkait ke dalam satu array
        $dosenArray = [
            'nip' => $dosen->nip,
            'slug' => $dosen->slug,
            'jafa' => $dosen->jafa,
            'jenis_kelamin' => $dosen->jenis_kelamin,
            'umur' => $dosen->umur,
            'biografi' => $dosen->biografi,
            'minat_penelitian' => $dosen->minat_penelitian,
            'link_gscholar' => $dosen->link_gscholar,
            'link_sinta' => $dosen->link_sinta,
            'link_scopus' => $dosen->link_scopus,
            'email' => $dosen->user->email,
            'name' => $dosen->user->name,
            'foto' => $dosen->user->foto,
            'pendidikan' => $dosen->pendidikans->pluck('pendidikan')->toArray(), // Mengambil semua pendidikan
            'bidang_kepakaran' => $dosen->bidangKepakarans->pluck('bidang_kepakaran')->toArray(), // Mengambil semua bidang kepakaran
            'penelitian' => $dosen->penelitians->pluck('judul')->toArray(), // Mengambil semua penelitian
            'pengabdian_masyarakat' => $dosen->pengabdianMasyarakats->pluck('judul')->toArray(), // Mengambil semua pengabdian masyarakat
            'publikasi' => $dosen->publikasis->map(function ($publikasi) {
                return [
                    'judul' => $publikasi->judul,
                    'link_publikasi' => $publikasi->link_publikasi
                ];
            })->toArray(), // Mengambil semua publikasi
        ];

        // dd($dosenArray);

        return view('pages.profil.dosen.detail', [
            'title' =>  $dosen->user->name . ' - Dosen',
            'active' => 'dosen',
            'dosen' => $dosenArray
        ]);
    }

    public function penelitianDanPkm($slug, $kategori)
    {
        if ($kategori !== 'publikasi-pilihan' && $kategori !== 'penelitian' && $kategori !== 'pengabdian-masyarakat') {
            return abort(404);
        }

        if ($kategori === 'publikasi-pilihan') {
            $namaKategori = 'Publikasi Pilihan';
        } else if ($kategori === 'penelitian') {
            $namaKategori = 'Penelitian';
        } else if ($kategori === 'pengabdian-masyarakat') {
            $namaKategori = 'Pengabdian Masyarakat';
        }
    
        return view('pages.profil.dosen.penelitian-dan-pkm', [
            'namaKategori' => $namaKategori
        ]);
    }

    public function fasilitas()
    {
        return view('pages.profil.fasilitas.index');
    }

    public function sarana()
    {
        return view('pages.profil.fasilitas.sarana.index');
    }

    public function prasarana()
    {
        return view('pages.profil.fasilitas.prasarana.index');
    }

    public function sistemInformasi()
    {
        return view('pages.profil.fasilitas.sistem-informasi');
    }
}
