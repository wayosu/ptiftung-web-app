<?php

namespace App\Http\Controllers;

use App\Models\SaranaTemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SaranaTemporaryImageController extends Controller
{
    public function uploadTemporaryImage(Request $request)
    {
        try {
            // Inisialisasi array kosong
            $name = [];
            $originalName = [];

            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $key => $value) {
                    // Membuat nama file
                    $image = uniqid() . time() . '.' . $value->getClientOriginalExtension();

                    // Menyimpan file ke direktori temporary
                    $storePath = 'fasilitas/sarana/tmp';
                    $value->storeAs($storePath, $image);

                    // Menyimpan setiap nama file dalam array
                    $name[] = $image;
                    $originalName[] = $value->getClientOriginalName();

                    // Menyimpan setiap nama file secara terpisah dalam database
                    SaranaTemporaryImage::create([
                        'file' => $image,
                        'user_id' => auth()->user()->id,
                    ]);
                }

                return response()->json([
                    'name' => $name,
                    'originalName' => $originalName
                ]);
            }
        } catch (\Exception $e) { // jika gagal mengupload file
            // \Log::error('File upload error: ' . $e->getMessage());
            // \Log::error('File upload error Trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getTemporaryImage()
    {
        try {
            // Mengambil ID pengguna yang sedang login
            $user_id = auth()->user()->id;

            // Mengambil nama file dari database
            $saranaTemporaryImages = SaranaTemporaryImage::where('user_id', $user_id)->pluck('file')->toArray();

            // Inisialisasi larik kosong untuk menyimpan detail file
            $data = [];

            // Menetapkan jalur dasar untuk gambar sementara
            $basePath = storage_path('app/public/fasilitas/sarana/tmp/');

            // Lakukan iterasi pada setiap nama file yang diperoleh dari database
            foreach ($saranaTemporaryImages as $file) {
                $filePath = $basePath . $file;

                // Periksa apakah file tersebut ada sebelum mengambil detailnya
                if (file_exists($filePath)) {

                    // Menyimpan detail file ke dalam array obj 
                    $obj = [
                        'name' => $file,
                        'size' => filesize($filePath),
                        'path' => url('storage/fasilitas/sarana/tmp/' . $file),
                    ];

                    // Menambahkan objek ke dalam array data
                    $data[] = $obj;
                }
            }

            // Mengembalikan respons JSON
            return response()->json($data);
        } catch (\Exception $e) { // jika bermasalah mengambil data
            // \Log::error('File loading error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function deleteTemporaryImage(Request $request)
    {
        try { // jika berhasil menghapus file

            // ambil data dari request dengan name 'filename'
            $filename =  $request->get('filename');

            // mengambil data dari database berdasarkan 'filename'
            $temporaryImage = SaranaTemporaryImage::where('file', $filename)->first();

            // jika data ditemukan
            if ($temporaryImage) {
                // menghapus file dari direktori tmp
                if (Storage::exists('fasilitas/sarana/tmp/' . $temporaryImage->file)) {
                    Storage::delete('fasilitas/sarana/tmp/' . $temporaryImage->file);
                }
                $temporaryImage->delete();
            }

            // mengembalikan respons JSON
            return response()->json(['success' => $filename]);
        } catch (\Exception $e) { // jika bermasalah menghapus file
            // \Log::error('File removal error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
