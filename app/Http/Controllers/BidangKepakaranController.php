<?php

namespace App\Http\Controllers;

use App\Models\BidangKepakaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class BidangKepakaranController extends Controller
{
    public function index(Request $request)
    {
        // jika ada request ajax
        if ($request->ajax()) {
            // ambil data
            $bidangKepakarans = BidangKepakaran::all();

            // transformasi data ke bentuk array
            $bidangKepakarans = $bidangKepakarans->transform(function ($item) {
                return $item;
            })->all();

            // tampilkan data dalam format DataTables
            return DataTables::of($bidangKepakarans)
                ->addColumn('aksi', function ($bidangKepakarans) {
                    return view('admin.pages.bidang-kepakaran.tombol-aksi', compact('bidangKepakarans'));
                })
                ->make(true);
        }

        // tampilkan halaman
        return view('admin.pages.bidang-kepakaran.index', [
            'icon' => 'fa-regular fa-lightbulb',
            'title' => 'Bidang Kepakaran',
            'subtitle' => 'Daftar Bidang Kepakaran',
            'active' => 'bidang-kepakaran',
        ]);
    }

    public function create()
    {
        // tampilkan halaman
        return view('admin.pages.bidang-kepakaran.form', [
            'icon' => 'plus',
            'title' => 'Bidang Kepakaran',
            'subtitle' => 'Tambah Bidang Kepakaran',
            'active' => 'bidang-kepakaran',
        ]);
    }

    public function store(Request $request)
    {
        // validasi data yang dikirim
        $request->validate([
            'bidang_kepakaran' => 'required|unique:bidang_kepakarans',
        ], [
            'bidang_kepakaran.required' => 'Bidang Kepakaran harus diisi!',
            'bidang_kepakaran.unique' => 'Bidang Kepakaran sudah ada!',
        ]);

        // simpan data
        BidangKepakaran::create([
            'bidang_kepakaran' => $request->bidang_kepakaran,
            'slug' => Str::slug($request->bidang_kepakaran),
        ]);

        // mengalihkan ke halaman bidang kepakaran -> index
        return redirect()->route('bidangKepakaran.index')->with('success', 'Bidang Kepakaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // cari data berdasarkan id
        $bidangKepakaran = BidangKepakaran::findOrFail($id);

        // tampilkan halaman
        return view('admin.pages.bidang-kepakaran.form', [
            'icon' => 'edit',
            'title' => 'Bidang Kepakaran',
            'subtitle' => 'Edit Bidang Kepakaran',
            'active' => 'bidang-kepakaran',
            'bidangKepakaran' => $bidangKepakaran
        ]);
    }

    public function update(Request $request, $id)
    {
        // validasi data yang dikirim
        $request->validate([
            'bidang_kepakaran' => 'required|unique:bidang_kepakarans,bidang_kepakaran,' . $id,
        ], [
            'bidang_kepakaran.required' => 'Bidang Kepakaran harus diisi!',
            'bidang_kepakaran.unique' => 'Bidang Kepakaran sudah ada!',
        ]);

        // cari data berdasarkan id
        $bidangKepakaran = BidangKepakaran::findOrFail($id);

        // update data
        $bidangKepakaran->update([
            'bidang_kepakaran' => $request->bidang_kepakaran,
        ]);

        // mengalihkan ke halaman bidang kepakaran -> index
        return redirect()->route('bidangKepakaran.index')->with('success', 'Bidang Kepakaran berhasil diubah!');
    }

    public function destroy($id)
    {
        // cari data berdasarkan id
        $bidangKepakaran = BidangKepakaran::findOrFail($id);

        // hapus data
        $bidangKepakaran->delete();

        // mengalihkan ke halaman bidang kepakaran -> index
        return redirect()->route('bidangKepakaran.index')->with('success', 'Bidang Kepakaran berhasil dihapus!');
    }
}
