<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => 'auth', 'prefix' => 'dasbor'], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dasbor');

    // Start Users //
    Route::group(['prefix' => 'pengguna'], function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('/{id}/reset-password', [App\Http\Controllers\UserController::class, 'formResetPassword'])->name('users.formResetPassword');
        Route::put('/{id}/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('users.resetPassword');
        Route::delete('/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

        // Admin //
        Route::get('/admin', [App\Http\Controllers\UserController::class, 'byAdmin'])->name('users.byAdmin');
        Route::get('/admin/create', [App\Http\Controllers\UserController::class, 'createAdmin'])->name('users.createAdmin');
        Route::post('/admin', [App\Http\Controllers\UserController::class, 'storeAdmin'])->name('users.storeAdmin');
        Route::get('/admin/{id}/edit', [App\Http\Controllers\UserController::class, 'editAdmin'])->name('users.editAdmin');
        Route::put('/admin/{id}', [App\Http\Controllers\UserController::class, 'updateAdmin'])->name('users.updateAdmin');

        // Dosen //
        Route::get('/dosen', [App\Http\Controllers\UserController::class, 'byDosen'])->name('users.byDosen');
        Route::get('/dosen/create', [App\Http\Controllers\UserController::class, 'createDosen'])->name('users.createDosen');
        Route::post('/dosen', [App\Http\Controllers\UserController::class, 'storeDosen'])->name('users.storeDosen');
        Route::get('/dosen/{id}/edit', [App\Http\Controllers\UserController::class, 'editDosen'])->name('users.editDosen');
        Route::put('/dosen/{id}', [App\Http\Controllers\UserController::class, 'updateDosen'])->name('users.updateDosen');

        // Mahasiswa //
        Route::get('/mahasiswa', [App\Http\Controllers\UserController::class, 'byMahasiswa'])->name('users.byMahasiswa');
        Route::get('/mahasiswa/create', [App\Http\Controllers\UserController::class, 'createMahasiswa'])->name('users.createMahasiswa');
        Route::post('/mahasiswa', [App\Http\Controllers\UserController::class, 'storeMahasiswa'])->name('users.storeMahasiswa');
        Route::get('/mahasiswa/{id}/edit', [App\Http\Controllers\UserController::class, 'editMahasiswa'])->name('users.editMahasiswa');
        Route::put('/mahasiswa/{id}', [App\Http\Controllers\UserController::class, 'updateMahasiswa'])->name('users.updateMahasiswa');
    });
    // End Users //

    // Start Bidang Kepakaran //
    Route::get('/bidang-kepakaran', [App\Http\Controllers\BidangKepakaranController::class, 'index'])->name('bidangKepakaran.index');
    Route::get('/bidang-kepakaran/create', [App\Http\Controllers\BidangKepakaranController::class, 'create'])->name('bidangKepakaran.create');
    Route::post('/bidang-kepakaran', [App\Http\Controllers\BidangKepakaranController::class, 'store'])->name('bidangKepakaran.store');
    Route::get('/bidang-kepakaran/{id}/edit', [App\Http\Controllers\BidangKepakaranController::class, 'edit'])->name('bidangKepakaran.edit');
    Route::put('/bidang-kepakaran/{id}', [App\Http\Controllers\BidangKepakaranController::class, 'update'])->name('bidangKepakaran.update');
    Route::delete('/bidang-kepakaran/{id}', [App\Http\Controllers\BidangKepakaranController::class, 'destroy'])->name('bidangKepakaran.destroy');
    // End Bidang Kepakaran //

    // Start Sejarah //
    Route::get('/sejarah', [App\Http\Controllers\ProfilProgramStudiController::class, 'sejarah'])->name('sejarah.index');
    Route::put('/sejarah', [App\Http\Controllers\ProfilProgramStudiController::class, 'updateSejarah'])->name('sejarah.update');
    // End Sejarah //

    // Start Visi Keilmuan, Tujuan, dan Strategi //
    Route::get('/visi-keilmuan-tujuan-strategi', [App\Http\Controllers\ProfilProgramStudiController::class, 'visiKeilmuanTujuanStrategi'])->name('visiKeilmuanTujuanStrategi.index');
    Route::put('/visi-keilmuan-tujuan-strategi', [App\Http\Controllers\ProfilProgramStudiController::class, 'updateVisiKeilmuanTujuanStrategi'])->name('visiKeilmuanTujuanStrategi.update');
    // End Visi Keilmuan, Tujuan, dan Strategi //

    // Start Struktur Organisasi //
    Route::get('/struktur-organisasi', [App\Http\Controllers\ProfilProgramStudiController::class, 'strukturOrganisasi'])->name('strukturOrganisasi.index');
    Route::put('/struktur-organisasi', [App\Http\Controllers\ProfilProgramStudiController::class, 'updateStrukturOrganisasi'])->name('strukturOrganisasi.update');
    // End Struktur Organisasi //

    // Start Kontak dan Lokasi //
    Route::get('/kontak-lokasi', [App\Http\Controllers\ProfilProgramStudiController::class, 'kontakLokasi'])->name('kontakLokasi.index');
    Route::put('/kontak-lokasi', [App\Http\Controllers\ProfilProgramStudiController::class, 'updateKontakLokasi'])->name('kontakLokasi.update');
    // End Kontak dan Lokasi //

    // Start Kategori Sarana //
    Route::get('/kategori-sarana', [App\Http\Controllers\SaranaKategoriController::class, 'index'])->name('kategoriSarana.index');
    Route::get('/kategori-sarana/create', [App\Http\Controllers\SaranaKategoriController::class, 'create'])->name('kategoriSarana.create');
    Route::post('/kategori-sarana', [App\Http\Controllers\SaranaKategoriController::class, 'store'])->name('kategoriSarana.store');
    Route::get('/kategori-sarana/{id}/edit', [App\Http\Controllers\SaranaKategoriController::class, 'edit'])->name('kategoriSarana.edit');
    Route::put('/kategori-sarana/{id}', [App\Http\Controllers\SaranaKategoriController::class, 'update'])->name('kategoriSarana.update');
    Route::delete('/kategori-sarana/{id}', [App\Http\Controllers\SaranaKategoriController::class, 'destroy'])->name('kategoriSarana.destroy');
    // End Kategori Sarana //

    // Start Sarana //
    Route::get('/sarana', [App\Http\Controllers\SaranaController::class, 'index'])->name('sarana.index');
    Route::get('/sarana/create', [App\Http\Controllers\SaranaController::class, 'create'])->name('sarana.create');
    Route::post('/sarana/upload-image', [App\Http\Controllers\SaranaTemporaryImageController::class, 'uploadTemporaryImage'])->name('sarana.uploadTemporaryImage');
    Route::get('/sarana/get-image', [App\Http\Controllers\SaranaTemporaryImageController::class, 'getTemporaryImage'])->name('sarana.getTemporaryImage');
    Route::delete('/sarana/delete-image', [App\Http\Controllers\SaranaTemporaryImageController::class, 'deleteTemporaryImage'])->name('sarana.deleteTemporaryImage');
    Route::get('/sarana/{id}/detail-image', [App\Http\Controllers\SaranaImageController::class, 'detailImage'])->name('sarana.detailImage');
    Route::delete('/sarana/{id}/delete-image', [App\Http\Controllers\SaranaImageController::class, 'deleteImage'])->name('sarana.deleteImage');
    Route::post('/sarana', [App\Http\Controllers\SaranaController::class, 'store'])->name('sarana.store');
    Route::get('/sarana/{id}/edit', [App\Http\Controllers\SaranaController::class, 'edit'])->name('sarana.edit');
    Route::put('/sarana/{id}', [App\Http\Controllers\SaranaController::class, 'update'])->name('sarana.update');
    Route::delete('/sarana/{id}', [App\Http\Controllers\SaranaController::class, 'destroy'])->name('sarana.destroy');
    // End Sarana //

    // Start Kategori Prasarana //
    Route::get('/kategori-prasarana', [App\Http\Controllers\PrasaranaKategoriController::class, 'index'])->name('kategoriPrasarana.index');
    Route::get('/kategori-prasarana/create', [App\Http\Controllers\PrasaranaKategoriController::class, 'create'])->name('kategoriPrasarana.create');
    Route::post('/kategori-prasarana', [App\Http\Controllers\PrasaranaKategoriController::class, 'store'])->name('kategoriPrasarana.store');
    Route::get('/kategori-prasarana/{id}/edit', [App\Http\Controllers\PrasaranaKategoriController::class, 'edit'])->name('kategoriPrasarana.edit');
    Route::put('/kategori-prasarana/{id}', [App\Http\Controllers\PrasaranaKategoriController::class, 'update'])->name('kategoriPrasarana.update');
    Route::delete('/kategori-prasarana/{id}', [App\Http\Controllers\PrasaranaKategoriController::class, 'destroy'])->name('kategoriPrasarana.destroy');
    // End Kategori Prasarana //

    // Start Prasarana //
    Route::get('/prasarana', [App\Http\Controllers\PrasaranaController::class, 'index'])->name('prasarana.index');
    Route::get('/prasarana/create', [App\Http\Controllers\PrasaranaController::class, 'create'])->name('prasarana.create');
    Route::post('/prasarana/upload-image', [App\Http\Controllers\PrasaranaTemporaryImageController::class, 'uploadTemporaryImage'])->name('prasarana.uploadTemporaryImage');
    Route::get('/prasarana/get-image', [App\Http\Controllers\PrasaranaTemporaryImageController::class, 'getTemporaryImage'])->name('prasarana.getTemporaryImage');
    Route::delete('/prasarana/delete-image', [App\Http\Controllers\PrasaranaTemporaryImageController::class, 'deleteTemporaryImage'])->name('prasarana.deleteTemporaryImage');
    Route::get('/prasarana/{id}/detail-image', [App\Http\Controllers\PrasaranaImageController::class, 'detailImage'])->name('prasarana.detailImage');
    Route::delete('/prasarana/{id}/delete-image', [App\Http\Controllers\PrasaranaImageController::class, 'deleteImage'])->name('prasarana.deleteImage');
    Route::post('/prasarana', [App\Http\Controllers\PrasaranaController::class, 'store'])->name('prasarana.store');
    Route::get('/prasarana/{id}/edit', [App\Http\Controllers\PrasaranaController::class, 'edit'])->name('prasarana.edit');
    Route::put('/prasarana/{id}', [App\Http\Controllers\PrasaranaController::class, 'update'])->name('prasarana.update');
    Route::delete('/prasarana/{id}', [App\Http\Controllers\PrasaranaController::class, 'destroy'])->name('prasarana.destroy');
    // End Prasarana //

    // Start Sistem Informasi //
    Route::get('/sistem-informasi', [App\Http\Controllers\SistemInformasiController::class, 'index'])->name('sistemInformasi.index');
    Route::get('/sistem-informasi/create', [App\Http\Controllers\SistemInformasiController::class, 'create'])->name('sistemInformasi.create');
    Route::post('/sistem-informasi', [App\Http\Controllers\SistemInformasiController::class, 'store'])->name('sistemInformasi.store');
    Route::get('/sistem-informasi/{id}/edit', [App\Http\Controllers\SistemInformasiController::class, 'edit'])->name('sistemInformasi.edit');
    Route::put('/sistem-informasi/{id}', [App\Http\Controllers\SistemInformasiController::class, 'update'])->name('sistemInformasi.update');
    Route::delete('/sistem-informasi/{id}', [App\Http\Controllers\SistemInformasiController::class, 'destroy'])->name('sistemInformasi.destroy');
    // End Sistem Informasi //

    // Start Profil Lulusan //
    Route::get('/profil-lulusan', [App\Http\Controllers\ProfilLulusanController::class, 'index'])->name('profilLulusan.index');
    Route::get('/profil-lulusan/create', [App\Http\Controllers\ProfilLulusanController::class, 'create'])->name('profilLulusan.create');
    Route::post('/profil-lulusan', [App\Http\Controllers\ProfilLulusanController::class, 'store'])->name('profilLulusan.store');
    Route::get('/profil-lulusan/{id}/edit', [App\Http\Controllers\ProfilLulusanController::class, 'edit'])->name('profilLulusan.edit');
    Route::put('/profil-lulusan/{id}', [App\Http\Controllers\ProfilLulusanController::class, 'update'])->name('profilLulusan.update');
    Route::delete('/profil-lulusan/{id}', [App\Http\Controllers\ProfilLulusanController::class, 'destroy'])->name('profilLulusan.destroy');
    // End Profil Lulusan //

    // Start Capaian Pembelajaran //
    Route::get('/capaian-pembelajaran', [App\Http\Controllers\CapaianPembelajaranController::class, 'index'])->name('capaianPembelajaran.index');
    Route::get('/capaian-pembelajaran/create', [App\Http\Controllers\CapaianPembelajaranController::class, 'create'])->name('capaianPembelajaran.create');
    Route::post('/capaian-pembelajaran', [App\Http\Controllers\CapaianPembelajaranController::class, 'store'])->name('capaianPembelajaran.store');
    Route::get('/capaian-pembelajaran/{id}/edit', [App\Http\Controllers\CapaianPembelajaranController::class, 'edit'])->name('capaianPembelajaran.edit');
    Route::put('/capaian-pembelajaran/{id}', [App\Http\Controllers\CapaianPembelajaranController::class, 'update'])->name('capaianPembelajaran.update');
    Route::delete('/capaian-pembelajaran/{id}', [App\Http\Controllers\CapaianPembelajaranController::class, 'destroy'])->name('capaianPembelajaran.destroy');
    // End Capaian Pembelajaran //

    // Start Kurikulum //
    Route::get('/kurikulum', [App\Http\Controllers\KurikulumController::class, 'index'])->name('kurikulum.index');
    Route::get('/kurikulum/create', [App\Http\Controllers\KurikulumController::class, 'create'])->name('kurikulum.create');
    Route::post('/kurikulum', [App\Http\Controllers\KurikulumController::class, 'store'])->name('kurikulum.store');
    Route::get('/kurikulum/{id}/edit', [App\Http\Controllers\KurikulumController::class, 'edit'])->name('kurikulum.edit');
    Route::put('/kurikulum/{id}', [App\Http\Controllers\KurikulumController::class, 'update'])->name('kurikulum.update');
    Route::delete('/kurikulum/{id}', [App\Http\Controllers\KurikulumController::class, 'destroy'])->name('kurikulum.destroy');
    // End Kurikulum //

    // Start Dokumen Kurikulum //
    Route::get('/dokumen-kurikulum', [App\Http\Controllers\DokumenKurikulumController::class, 'index'])->name('dokumenKurikulum.index');
    Route::post('/dokumen-kurikulum', [App\Http\Controllers\DokumenKurikulumController::class, 'store'])->name('dokumenKurikulum.store');
    Route::post('/dokumen-kurikulum/update-status', [App\Http\Controllers\DokumenKurikulumController::class, 'updateStatus'])->name('dokumenKurikulum.updateStatus');
    Route::delete('/dokumen-kurikulum/{id}', [App\Http\Controllers\DokumenKurikulumController::class, 'destroy'])->name('dokumenKurikulum.destroy');
    // End Dokumen Kurikulum //

    // Start Pengaturan Akun //
    Route::get('/pengaturan-akun', [App\Http\Controllers\PengaturanController::class, 'pengaturanAkun'])->name('pengaturanAkun');
    Route::put('/pengaturan-akun', [App\Http\Controllers\PengaturanController::class, 'updateInformasiAkun'])->name('informasiAkun.update');
    Route::put('/pengaturan-akun/password', [App\Http\Controllers\PengaturanController::class, 'updatePasswordAkun'])->name('passwordAkun.update');
    // End Pengaturan Akun //

    // Start Pengaturan Sistem //
    Route::get('/pengaturan-sistem', [App\Http\Controllers\PengaturanController::class, 'pengaturanSistem'])->name('pengaturanSistem');
    Route::put('/pengaturan-sistem', [App\Http\Controllers\PengaturanController::class, 'updatePengaturanSistem'])->name('pengaturanSistem.update');
    Route::put('/ubah-logo', [App\Http\Controllers\PengaturanController::class, 'updateLogo'])->name('pengaturanSistem.updateLogo');
    Route::post('/setel-ke-pengaturan-pabrik', [App\Http\Controllers\PengaturanController::class, 'resetToFactory'])->name('pengaturanSistem.resetToFactory');
    // End Pengaturan Sistem //

});
