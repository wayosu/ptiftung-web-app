<?php

namespace App\Http\Controllers;

use App\Models\SaranaImage;
use Illuminate\Support\Facades\Storage;

class SaranaImageController extends Controller
{
    public function detailImage($id)
    {
        try {
            // ambil data dari model SaranaImage berdasarkan sarana_id
            $saranaImages = SaranaImage::where('sarana_id', $id)->pluck('gambar');

            // Periksa apakah gambar yang diambil kosong
            if ($saranaImages->isEmpty()) {
                return redirect()->route('sarana.index')->with('error', 'Data gambar bermasalah. Data gambar tidak ditemukan!');
            }

            // tampilkan halaman
            return view('admin.pages.fasilitas.sarana.gambar', [
                'icon' => 'fa-regular fa-images',
                'title' => 'Sarana',
                'subtitle' => 'Detail Gambar Sarana',
                'active' => 'sarana',
                'saranaImages' => $saranaImages
            ]);
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('sarana.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function deleteImage($id)
    {
        try {
            // cari data dari model SaranaImage berdasarkan id
            $saranaImage = SaranaImage::findOrFail($id);

            if (Storage::exists('fasilitas/sarana/' . $saranaImage->gambar)) {
                Storage::delete('fasilitas/sarana/' . $saranaImage->gambar);
            }

            $saranaImage->delete();

            return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->back()->with('error', 'Gambar gagal dihapus!');
        }
    }
}
