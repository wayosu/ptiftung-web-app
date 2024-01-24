<?php

namespace App\Http\Controllers;

use App\Models\BidangKepakaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class BidangKepakaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.bidang-kepakaran.bidangKepakaran', [
            'icon' => 'list',
            'title' => 'Bidang Kepakaran',
            'subtitle' => 'Daftar Bidang Kepakaran',
            'active' => 'bidangKepakaran',
        ]);
    }

    public function indexAjax()
    {
        // ambil data
        $data = BidangKepakaran::orderBy('bidang_kepakaran', 'asc')->get();

        // kembalikan response
        return DataTables::of($data)
            ->addColumn('aksi', function ($data) {
                return view('admin.components.bidang-kepakaran.tombolAksi', compact('data'));
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'bidangKepakaran' => 'required',
        ], [
            'bidangKepakaran.required' => 'Bidang Kepakaran harus diisi!',
        ]);

        // Cek validasi
        if ($validasi->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'errors' => $validasi->errors()->all(),
                'message' => 'Data gagal disimpan!',
            ]);
        } else {
            $bidangKepakaran = new BidangKepakaran();
            $bidangKepakaran->bidang_kepakaran = $request->bidangKepakaran;
            $bidangKepakaran->slug = Str::slug($request->bidangKepakaran);
            $bidangKepakaran->save();

            return response()->json([
                'status' => 'success',
                'code' => '200',
                'message' => 'Data berhasil disimpan!',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // ambil data berdasarkan id
        $data = BidangKepakaran::findOrFail($id);

        // kembalikan response
        return response()->json([
            'status' => 'success',
            'code' => '200',
            'result' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validasi = Validator::make($request->all(), [
            'bidangKepakaran' => 'required',
        ], [
            'bidangKepakaran.required' => 'Bidang Kepakaran harus diisi!',
        ]);

        // Cek validasi
        if ($validasi->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => '400',
                'errors' => $validasi->errors()->all(),
                'message' => 'Data gagal disimpan!',
            ]);
        } else {
            $bidangKepakaran = BidangKepakaran::findOrFail($id);
            $bidangKepakaran->bidang_kepakaran = $request->bidangKepakaran;
            $bidangKepakaran->slug = Str::slug($request->bidangKepakaran);
            $bidangKepakaran->save();

            return response()->json([
                'status' => 'success',
                'code' => '200',
                'message' => 'Data berhasil diubah!',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bidangKepakaran = BidangKepakaran::findOrFail($id);
        $bidangKepakaran->delete();

        return response()->json([
            'status' => 'success',
            'code' => '200',
            'message' => 'Data berhasil dihapus!',
        ]);
    }
}
