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

        return view('admin.pages.bidang-kepakaran.index', [
            'icon' => 'list',
            'title' => 'Bidang Kepakaran',
            'subtitle' => 'Daftar Bidang Kepakaran',
            'active' => 'bidangKepakaran',
        ]);
    }

    public function create()
    {
        return view('admin.pages.bidang-kepakaran.form', [
            'icon' => 'plus',
            'title' => 'Bidang Kepakaran',
            'subtitle' => 'Tambah Bidang Kepakaran',
            'active' => 'bidangKepakaran',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bidang_kepakaran' => 'required|unique:bidang_kepakarans',
        ], [
            'bidang_kepakaran.required' => 'Bidang Kepakaran harus diisi!',
            'bidang_kepakaran.unique' => 'Bidang Kepakaran sudah ada!',
        ]);

        BidangKepakaran::create([
            'bidang_kepakaran' => $request->bidang_kepakaran,
            'slug' => Str::slug($request->bidang_kepakaran),
        ]);

        return redirect()->route('bidangKepakaran.index')->with('success', 'Bidang Kepakaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // ambil data
        $bidangKepakaran = BidangKepakaran::findOrFail($id);

        return view('admin.pages.bidang-kepakaran.form', [
            'icon' => 'edit',
            'title' => 'Bidang Kepakaran',
            'subtitle' => 'Edit Bidang Kepakaran',
            'active' => 'bidangKepakaran',
            'bidangKepakaran' => $bidangKepakaran
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bidang_kepakaran' => 'required|unique:bidang_kepakarans,bidang_kepakaran,' . $id,
        ], [
            'bidang_kepakaran.required' => 'Bidang Kepakaran harus diisi!',
            'bidang_kepakaran.unique' => 'Bidang Kepakaran sudah ada!',
        ]);

        $bidangKepakaran = BidangKepakaran::findOrFail($id);

        $bidangKepakaran->update([
            'bidang_kepakaran' => $request->bidang_kepakaran,
        ]);

        return redirect()->route('bidangKepakaran.index')->with('success', 'Bidang Kepakaran berhasil diubah!');
    }

    public function destroy($id)
    {
        $bidangKepakaran = BidangKepakaran::findOrFail($id);
        $bidangKepakaran->delete();

        return redirect()->route('bidangKepakaran.index')->with('success', 'Bidang Kepakaran berhasil dihapus!');
    }
}
