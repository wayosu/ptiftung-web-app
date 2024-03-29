<?php

namespace App\Http\Controllers;

use App\Models\KegiatanPerkuliahanImage;
use Illuminate\Support\Facades\Storage;

class KegiatanPerkuliahanImageController extends Controller
{
    public function detailImage($id)
    {
        try {
            // ambil data dari model KegiatanPerkuliahanImage berdasarkan sarana_id
            $kegiatanPerkuliahanImages = KegiatanPerkuliahanImage::where('kegiatan_perkuliahan_id', $id)->pluck('gambar');

            // Periksa apakah gambar yang diambil kosong
            if ($kegiatanPerkuliahanImages->isEmpty()) {
                return redirect()->route('kegiatanPerkuliahan.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
            }

            // tampilkan halaman
            return view('admin.pages.akademik.kegiatan-perkuliahan.gambar', [
                'icon' => 'fa-regular fa-images',
                'title' => 'Kegiatan Perkuliahan',
                'subtitle' => 'Detail Dokumentasi Kegiatan',
                'active' => 'kegiatan-perkuliahan',
                'kegiatanPerkuliahanImages' => $kegiatanPerkuliahanImages
            ]);
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('kegiatanPerkuliahan.index')->with('error', 'Halaman sedang bermasalah.');
        }
    }

    public function deleteImage($id)
    {
        try {
            // cari data dari model KegiatanPerkuliahanImage berdasarkan id
            $kegiatanPerkuliahanImage = KegiatanPerkuliahanImage::findOrFail($id);

            if (Storage::exists('akademik/kegiatan-perkuliahan/' . $kegiatanPerkuliahanImage->gambar)) {
                Storage::delete('akademik/kegiatan-perkuliahan/' . $kegiatanPerkuliahanImage->gambar);
            }

            $kegiatanPerkuliahanImage->delete();

            return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->back()->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->back()->with('error', 'Gambar gagal dihapus!');
        }
    }
}
