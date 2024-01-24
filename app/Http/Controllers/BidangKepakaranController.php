<?php

namespace App\Http\Controllers;

use App\Models\BidangKepakaran;
use Illuminate\Http\Request;

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
    public function show(BidangKepakaran $bidangKepakaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BidangKepakaran $bidangKepakaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BidangKepakaran $bidangKepakaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BidangKepakaran $bidangKepakaran)
    {
        //
    }
}
