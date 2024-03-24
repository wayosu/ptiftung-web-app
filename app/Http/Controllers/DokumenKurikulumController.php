<?php

namespace App\Http\Controllers;

use App\Models\DokumenKurikulum;
use Illuminate\Http\Request;

class DokumenKurikulumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // tampilkan halaman
        return view('admin.pages.akademik.dokumen-kurikulum.index', [
            'icon' => 'fa-solid fa-graduation-cap',
            'title' => 'Dokumen Kurikulum',
            'subtitle' => 'Daftar Dokumen Kurikulum',
            'active' => 'dokumen-kurikulum',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DokumenKurikulum $dokumenKurikulum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DokumenKurikulum $dokumenKurikulum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DokumenKurikulum $dokumenKurikulum)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DokumenKurikulum $dokumenKurikulum)
    {
        //
    }
}
