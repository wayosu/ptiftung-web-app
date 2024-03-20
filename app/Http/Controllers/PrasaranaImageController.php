<?php

namespace App\Http\Controllers;

use App\Models\PrasaranaImage;
use Illuminate\Support\Facades\Storage;

class PrasaranaImageController extends Controller
{
    public function detailImage($id)
    {
        try {
            // ambil data dari model PrasaranaImage berdasarkan sarana_id
            $prasaranaImages = PrasaranaImage::where('prasarana_id', $id)->pluck('gambar');

            // Periksa apakah gambar yang diambil kosong
            if ($prasaranaImages->isEmpty()) {
                return redirect()->route('prasarana.index')->with('error', 'Data gambar bermasalah. Data gambar tidak ditemukan!');
            }

            // tampilkan halaman
            return view('admin.pages.fasilitas.prasarana.gambar', [
                'icon' => 'fa-regular fa-images',
                'title' => 'Prasarana',
                'subtitle' => 'Detail Gambar Prasarana',
                'active' => 'prasarana',
                'prasaranaImages' => $prasaranaImages
            ]);
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('prasarana.index')->with('error', 'Halaman sedang bermasalah.');
        }
    }

    public function deleteImage($id)
    {
        try {
            // cari data dari model PrasaranaImage berdasarkan id
            $prasaranaImage = PrasaranaImage::findOrFail($id);

            if (Storage::exists('fasilitas/prasarana/' . $prasaranaImage->gambar)) {
                Storage::delete('fasilitas/prasarana/' . $prasaranaImage->gambar);
            }

            $prasaranaImage->delete();

            return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->back()->with('error', 'Data gambar bermasalah. Data gambar tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->back()->with('error', 'Gambar gagal dihapus!');
        }
    }
}
