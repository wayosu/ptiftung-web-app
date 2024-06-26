<?php

namespace App\Http\Controllers;

use App\Models\Prasarana;
use App\Models\PrasaranaKategori;
use App\Models\PrasaranaTemporaryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class PrasaranaController extends Controller
{
    private function checkSuperadminAdminKajur()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Superadmin') || $userAuth->memilikiperan('Admin') || $userAuth->	memilikiperan('Kajur'); 
    }

    private function checkKaprodi()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Kaprodi');
    }
    
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            if ($this->checkSuperadminAdminKajur()) {
                $prasaranas = Prasarana::with(['createdBy', 'prasaranaKategori'])->orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi()) {
                $prasaranas = Prasarana::with(['createdBy', 'prasaranaKategori'])
                    ->where('program_studi', Auth::user()->dosen->program_studi)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            // transformasi data ke bentuk array
            $prasaranas = $prasaranas->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($prasaranas)
                ->addColumn('aksi', function ($prasaranas) {
                    return view('admin.pages.fasilitas.prasarana.tombol-aksi', compact('prasaranas'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.fasilitas.prasarana.index', [
            'icon' => 'fa-regular fa-hospital',
            'title' => 'Prasarana',
            'subtitle' => 'Daftar Prasarana',
            'active' => 'prasarana',
        ]);
    }

    public function create()
    {
        try {
            // ambil data dari mode PrasaranaKategori. lalu hanya ambil id dan prasarana_kategori saja
            $prasaranaKategoris = PrasaranaKategori::orderBy('prasarana_kategori', 'asc')->get(['id', 'prasarana_kategori']);

            // tampilkan halaman
            return view('admin.pages.fasilitas.prasarana.form', [
                'icon' => 'plus',
                'title' => 'Prasarana',
                'subtitle' => 'Tambah Prasarana',
                'active' => 'prasarana',
                'prasaranaKategoris' => $prasaranaKategoris
            ]);
        } catch (\Exception $e) {  // jika bermasalah mengambil data
            return redirect()->route('prasarana.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'keterangan' => 'required|unique:prasaranas,keterangan',
            'prasarana_kategori_id' => 'required',
        ];
    
        $validationMessages = [
            'keterangan.required' => 'Keterangan harus diisi!',
            'keterangan.unique' => 'Keterangan sudah ada!',
            'prasarana_kategori_id.required' => 'Kategori Prasarana harus dipilih!',
        ];
    
        if ($this->checkSuperadminAdminKajur()) {
            $validationRules['program_studi'] = 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI';
            $validationMessages['program_studi.required'] = 'Program Studi harus dipilih!';
            $validationMessages['program_studi.in'] = 'Program Studi tidak valid!';
        }
    
        $request->validate($validationRules, $validationMessages);

        if ($this->checkSuperadminAdminKajur()) {
            $program_studi = $request->program_studi;
        } else if ($this->checkKaprodi()) {
            $program_studi = Auth::user()->dosen->program_studi;
        } else {
            return redirect()->route('prasarana.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses menambahkan data

            // simpan data
            $prasarana = Prasarana::create([
                'keterangan' => $request->keterangan,
                'slug' => Str::slug($request->keterangan),
                'prasarana_kategori_id' => $request->prasarana_kategori_id,
                'program_studi' => $program_studi,
                'created_by' => Auth::user()->id,
            ]);

            // ambil data dari table prasarana temporary image
            $prasaranaTemporaryImages = PrasaranaTemporaryImage::where('user_id', Auth::user()->id)->get();

            // looping data dari table prasarana temporary image
            foreach ($prasaranaTemporaryImages as $prasaranaTemporaryImage) {
                // salin file dari prasarana tmp ke prasarana
                Storage::copy('fasilitas/prasarana/tmp/' . $prasaranaTemporaryImage->file, 'fasilitas/prasarana/' . $prasaranaTemporaryImage->file);

                // simpan data gambar ke database
                $prasarana->prasaranaImages()->create([
                    'gambar' => $prasaranaTemporaryImage->file,
                ]);

                // hapus file dari prasarana tmp
                Storage::delete('fasilitas/prasarana/tmp/' . $prasaranaTemporaryImage->file);
            }

            // hapus semua data di table prasarana temporary image berdasarkan user_id
            PrasaranaTemporaryImage::where('user_id', Auth::user()->id)->delete();

            return redirect()->route('prasarana.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('prasarana.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        try { // jika id ditemukan
            // ambil data dari model Prasarana berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $prasarana = Prasarana::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $prasarana = Prasarana::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // ambil data dari mode PrasaranaKategori. lalu hanya ambil id dan prasarana_kategori saja
            $prasaranaKategoris = PrasaranaKategori::orderBy('prasarana_kategori', 'asc')->get(['id', 'prasarana_kategori']);

            // tampilkan halaman
            return view('admin.pages.fasilitas.prasarana.form', [
                'icon' => 'edit',
                'title' => 'Prasarana',
                'subtitle' => 'Edit Prasarana',
                'active' => 'prasarana',
                'prasarana' => $prasarana,
                'prasaranaKategoris' => $prasaranaKategoris
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('prasarana.index')->with('error', 'Halaman bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika bermasalah mengambil data
            return redirect()->route('prasarana.index')->with('error', 'Halaman sedang bermasalah!');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'keterangan' => 'required|unique:prasaranas,keterangan,' . $id,
            'prasarana_kategori_id' => 'required',
        ];
    
        $validationMessages = [
            'keterangan.required' => 'Keterangan harus diisi!',
            'keterangan.unique' => 'Keterangan sudah ada!',
            'sarana_kategori_id.required' => 'Kategori Sarana harus dipilih!',
        ];
    
        if ($this->checkSuperadminAdminKajur()) {
            $validationRules['program_studi'] = 'required|in:SISTEM INFORMASI,PEND. TEKNOLOGI INFORMASI';
            $validationMessages['program_studi.required'] = 'Program Studi harus dipilih!';
            $validationMessages['program_studi.in'] = 'Program Studi tidak valid!';
        }
    
        $request->validate($validationRules, $validationMessages);

        if ($this->checkSuperadminAdminKajur()) {
            $program_studi = $request->program_studi;
        } else if ($this->checkKaprodi()) {
            $program_studi = Auth::user()->dosen->program_studi;
        } else {
            return redirect()->route('prasarana.index')->with('error', 'Data gagal diperbarui!. Anda tidak mempunyai hak akses.');
        }

        try { // jika sukses update data
            // ambil data dari model Prasarana berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $prasarana = Prasarana::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $prasarana = Prasarana::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // update data dari model Prasarana
            $prasarana->update([
                'keterangan' => $request->keterangan,
                'slug' => Str::slug($request->keterangan),
                'prasarana_kategori_id' => $request->prasarana_kategori_id,
                'program_studi' => $program_studi,
                'updated_by' => Auth::user()->id,
            ]);

            // ambil data dari table prasarana temporary image
            $prasaranaTemporaryImages = PrasaranaTemporaryImage::where('user_id', Auth::user()->id)->get();

            // looping data dari table prasarana temporary image
            foreach ($prasaranaTemporaryImages as $prasaranaTemporaryImage) {
                // salin file dari prasarana tmp ke prasarana
                Storage::copy('fasilitas/prasarana/tmp/' . $prasaranaTemporaryImage->file, 'fasilitas/prasarana/' . $prasaranaTemporaryImage->file);

                // simpan data gambar ke database
                $prasarana->prasaranaImages()->create([
                    'gambar' => $prasaranaTemporaryImage->file,
                ]);

                // hapus file dari prasarana tmp
                Storage::delete('fasilitas/prasarana/tmp/' . $prasaranaTemporaryImage->file);
            }

            // hapus semua data di table prasarana temporary image berdasarkan user_id
            PrasaranaTemporaryImage::where('user_id', Auth::user()->id)->delete();

            return redirect()->route('prasarana.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('prasarana.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal memperbarui data
            return redirect()->route('prasarana.index')->with('error', 'Data gagal diperbarui!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model Prasarana berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $prasarana = Prasarana::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $prasarana = Prasarana::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // loop data dari table prasarana image
            foreach ($prasarana->prasaranaImages as $prasaranaImage) {
                // hapus file dari storage/penyimpanan
                if (Storage::exists('fasilitas/prasarana/' . $prasaranaImage->gambar)) {
                    Storage::delete('fasilitas/prasarana/' . $prasaranaImage->gambar);
                }

                // hapus gambar dari table prasarana image
                $prasaranaImage->delete();
            }

            // hapus data dari table prasarana
            $prasarana->delete();

            return redirect()->route('prasarana.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('prasarana.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('prasarana.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
