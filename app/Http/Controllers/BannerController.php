<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $banners = Banner::with('createdBy')->orderBy('created_at', 'desc')->get();

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
        // validasi data yang dikirim
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'banner.required' => 'Banner harus diisi!',
            'banner.image' => 'File Banner harus berupa gambar!',
            'banner.mimes' => 'File Banner harus berupa jpeg, png, jpg!',
            'banner.max' => 'File Banner tidak boleh lebih dari 2 MB!',
        ]);

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
            $banner = Banner::findOrFail($id);

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
