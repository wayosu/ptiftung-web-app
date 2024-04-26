<?php

namespace App\Http\Controllers\FrontPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function sejarah()
    {
        return view('pages.profil.sejarah');
    }

    public function visiTujuanStrategi()
    {
        return view('pages.profil.visi-tujuan-strategi');
    }

    public function strukturOrganisasi()
    {
        return view('pages.profil.struktur-organisasi');
    }

    public function dosen()
    {
        return view('pages.profil.dosen.index');
    }

    public function detailDosen($slug)
    {
        return view('pages.profil.dosen.detail');
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
