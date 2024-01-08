<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // check auth user
        if (!auth()->user()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        } else {
            $data = User::with('roles')->orderBy('created_at', 'desc')->get();
            $data = $data->transform(function ($item) {
                $item->role_names = Str::ucfirst($item->roles->pluck('name')->implode(', '));
                return $item;
            })->all();

            return DataTables::of($data)
                ->addColumn('aksi', function ($data) {
                    return view('admin.components.users.tombolAksi', compact('data'));
                })
                ->make(true);
        }
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
