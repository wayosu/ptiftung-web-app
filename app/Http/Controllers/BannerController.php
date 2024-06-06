<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class BannerController extends Controller
{
    private function checkSuperadminAdminKajur()
    {
        $userAuth = Auth::user();
        return $userAuth->memilikiperan('Superadmin') || $userAuth->memilikiperan('Admin') || $userAuth->memilikiperan('Kajur'); 
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
                $banners = Banner::with('createdBy')->orderBy('created_at', 'desc')->get();
            } else if ($this->checkKaprodi()) {
                $banners = Banner::with('createdBy')->where('program_studi', Auth::user()->dosen->program_studi)->orderBy('created_at', 'desc')->get();
            }

            // transformasi data ke bentuk array
            $banners = $banners->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($banners)
                ->addColumn('aksi', function ($banners) {
                    return view('admin.pages.konten.banner.tombol-aksi', compact('banners'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.konten.banner.index', [
            'icon' => 'layout',
            'title' => 'Banner',
            'subtitle' => 'Daftar Banner',
            'active' => 'banner',
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim
        $validationRules = [
            'banner' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    
        $validationMessages = [
            'banner.required' => 'Banner harus diisi!',
            'banner.image' => 'File Banner harus berupa gambar!',
            'banner.mimes' => 'File Banner harus berupa jpeg, png, jpg!',
            'banner.max' => 'File Banner tidak boleh lebih dari 2 MB!',
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
            return redirect()->route('banner.index')->with('error', 'Data gagal ditambahkan!. Anda tidak mempunyai hak akses.');
        }

        try { // jika data valid
            if ($request->hasFile('banner')) {
                // namakan file
                $nameFile = uniqid() . time() . '.' . $request->file('banner')->getClientOriginalExtension();

                // simpan file ke storage/penyimpanan
                $storePath = 'konten/banner';
                $request->file('banner')->storeAs($storePath, $nameFile);

                // simpan data
                Banner::create([
                    'gambar' => $nameFile,
                    'program_studi' => $program_studi,
                    'created_by' => auth()->user()->id,
                ]);

                return redirect()->route('banner.index')->with('success', 'Data berhasil ditambahkan.');
            } else {
                return redirect()->route('banner.index')->with('error', 'Data gagal ditambahkan. File banner tidak boleh kosong!');
            }
        } catch (\Exception $e) { // jika gagal menambahkan data
            return redirect()->route('banner.index')->with('error', 'Data gagal ditambahkan!');
        }
    }

    public function destroy($id)
    {
        try { // jika id ditemukan lakukan proses delete
            // ambil data dari model Banner berdasarkan id
            if ($this->checkSuperadminAdminKajur()) {
                $banner = Banner::findOrFail($id);
            } else if ($this->checkKaprodi()) {
                $banner = Banner::where('program_studi', Auth::user()->dosen->program_studi)->findOrFail($id);
            }

            // hapus file dari storage/penyimpanan
            if (Storage::exists('konten/banner/' . $banner->gambar)) {
                Storage::delete('konten/banner/' . $banner->gambar);
            }

            // hapus data dari table banner
            $banner->delete();

            return redirect()->route('banner.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) { // jika id tidak ditemukan
            return redirect()->route('banner.index')->with('error', 'Data bermasalah. Data tidak ditemukan!');
        } catch (\Exception $e) { // jika gagal menghapus data
            return redirect()->route('banner.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
