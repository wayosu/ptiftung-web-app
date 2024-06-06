<?php

namespace App\Http\Controllers\FrontPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        // ambil banner untuk program studi PEND. TEKNOLOGI INFORMASI
        $banners = \App\Models\Banner::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->pluck('gambar')->toArray();

        // ambil visi keilmuan dari profil program studi PEND. TEKNOLOGI INFORMASI
        $visiKeilmuan = \App\Models\ProfilProgramStudi::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->firstOrFail('visi_keilmuan')->visi_keilmuan;

        // ambil video profil dari profil program studi PEND. TEKNOLOGI INFORMASI
        $videoProfil = \App\Models\ProfilProgramStudi::where('program_studi', 'PEND. TEKNOLOGI INFORMASI')->firstOrFail('link_embed_video_profil')->link_embed_video_profil;

        // ambil 3 berita terkini
        $beritas = \App\Models\Berita::orderBy('created_at', 'desc')->take(3)->get();

        // ambil 3 agenda terkini
        $agendas = \App\Models\Agenda::orderBy('created_at', 'desc')->take(3)->get();

        // dd($videoProfil);

        return view('pages.beranda', [
            'title' => 'Beranda',
            'banners' => $banners,
            'visiKeilmuan' => $visiKeilmuan,
            'videoProfil' => $videoProfil,
            'beritas' => $beritas,
            'agendas' => $agendas
        ]);
    }
}
