<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function showAll()
    {
        $notifikasisBelumDibaca = Notifikasi::where('user_id', auth()->user()->id)
            ->where('dibaca', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $notifikasisSudahDibaca = Notifikasi::where('user_id', auth()->user()->id)
            ->where('dibaca', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.notifikasi', [
            'icon' => 'bell',
            'title' => 'Notifikasi',
            'notifikasisBelumDibaca' => $notifikasisBelumDibaca,
            'notifikasisSudahDibaca' => $notifikasisSudahDibaca
        ]);
    }

    public function jumlah()
    {
        $unreadCount = Notifikasi::where('dibaca', false)->count();
        return response()->json(['unread_count' => $unreadCount]);
    }

    public function dibaca(Request $request)
    {
        $notifikasi = Notifikasi::find($request->id);
        if ($notifikasi) {
            $notifikasi->dibaca = 1;
            $notifikasi->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function destroy(Request $request)
    {
        $notifikasi = Notifikasi::find($request->id);
        if ($notifikasi) {
            $notifikasi->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
