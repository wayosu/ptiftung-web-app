<?php

namespace App\Http\Controllers\FrontPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkademikController extends Controller
{
    public function profilLulusan()
    {
        // cek jika ada request
        if (request()->ajax()) {
            
        }

        return view('pages.akademik.profilLulusan', [
            'title' => 'Sejarah',
            'active' => 'sejarah',
        ]);
    }

    public function getAPIProfilLulusan()
    {
        try {
            $profilLulusan = \App\Models\ProfilLulusan::orderBy('judul', 'asc')->get();

            // transformasi data ke bentuk array
            $profilLulusan = $profilLulusan->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'subjudul' => $item->subjudul,
                    'deskripsi' => $item->deskripsi,
                    'gambar' => asset('storage/akademik/profil-lulusan/' . $item->gambar),
                ];
            })->all();
            
            return response()->json($profilLulusan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan pada server'], 500);
        }
    }
}
