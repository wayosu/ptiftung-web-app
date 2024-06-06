<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('admin.layouts.sidebar', function ($view) {
            if (auth()->user()->hasRole('Superadmin') || auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Kajur')) {
                $view->with('dataKegiatanMahasiswas', \App\Models\KegiatanMahasiswa::orderBy('nama_kegiatan', 'asc')->get(['nama_kegiatan', 'slug', 'program_studi']));
            } else if (auth()->user()->hasRole('Kaprodi') || auth()->user()->hasRole('Dosen')) {
                $view->with('dataKegiatanMahasiswas', \App\Models\KegiatanMahasiswa::where('program_studi', auth()->user()->dosen->program_studi)->orderBy('nama_kegiatan', 'asc')->get(['nama_kegiatan', 'slug']));
            } else if (auth()->user()->hasRole('Mahasiswa')) {
                // Ambil data user yang sedang login
                $user = auth()->user();

                // Ambil data mahasiswa yang terkait dengan user
                $mahasiswa = $user->mahasiswa;

                // Ambil semua kegiatan yang dimiliki oleh mahasiswa
                $kegiatans = \App\Models\Kegiatan::where('mahasiswa_id', $mahasiswa->id)->get();

                // Ambil semua kegiatan mahasiswa yang terkait dengan kegiatan yang dimiliki oleh mahasiswa
                $kegiatanMahasiswaIds = $kegiatans->pluck('kegiatan_mahasiswa_id')->unique();

                // Ambil data kegiatan mahasiswa berdasarkan ID yang telah diambil
                $kegiatanMahasiswas = \App\Models\KegiatanMahasiswa::whereIn('id', $kegiatanMahasiswaIds)->get();

                // Kirim data ke view
                $view->with('dataKegiatanMahasiswas', $kegiatanMahasiswas);
            }
        });

        view()->composer('admin.partials.notifikasi', function ($view) {
            // $view->with('notifikasis', \App\Models\Notifikasi::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get());

            // $view->with('unreadNotifikasis', \App\Models\Notifikasi::where('user_id', auth()->user()->id)->where('dibaca', 0)->orderBy('created_at', 'desc')->get());
        });
    }
}
