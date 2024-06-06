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

Route::get('/', [App\Http\Controllers\FrontPage\BerandaController::class, 'index'])->name('beranda');

Route::group(['as' => 'profil.'], function () {
    Route::get('/sejarah', [App\Http\Controllers\FrontPage\ProfilController::class, 'sejarah'])->name('sejarah');
    Route::get('/visi-tujuan-strategi', [App\Http\Controllers\FrontPage\ProfilController::class, 'visiTujuanStrategi'])->name('visiTujuanStrategi');
    Route::get('/struktur-organisasi', [App\Http\Controllers\FrontPage\ProfilController::class, 'strukturOrganisasi'])->name('strukturOrganisasi');
    Route::get('/dosen', [App\Http\Controllers\FrontPage\ProfilController::class, 'dosen'])->name('dosen');
    Route::get('/dosen/{slug}', [App\Http\Controllers\FrontPage\ProfilController::class, 'detailDosen'])->name('detailDosen');
    Route::get('/dosen/{slug}/{kategori}', [App\Http\Controllers\FrontPage\ProfilController::class, 'penelitianDanPkm'])->name('penelitianDosen');
});

Route::group(['prefix' => 'fasilitas', 'as' => 'fasilitas.'], function () {
    Route::get('/', [App\Http\Controllers\FrontPage\ProfilController::class, 'fasilitas'])->name('index');
    Route::get('/sarana', [App\Http\Controllers\FrontPage\ProfilController::class, 'sarana'])->name('sarana.index');
    Route::get('/prasarana', [App\Http\Controllers\FrontPage\ProfilController::class, 'prasarana'])->name('prasarana.index');
    Route::get('/sistem-informasi', [App\Http\Controllers\FrontPage\ProfilController::class, 'sistemInformasi'])->name('sistemInformasi.index');
});

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth', 'prefix' => 'dasbor'], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dasbor');

    // Start Users //
    Route::group(['middleware' => 'role:Superadmin|Admin', 'prefix' => 'pengguna'], function () {
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

        // Import Mahasiswa
        Route::post('/import-mahasiswa', [App\Http\Controllers\UserController::class, 'importMahasiswa'])->name('users.importMahasiswa');

        // Change Role
        Route::post('/change-role/{id}', [App\Http\Controllers\UserController::class, 'changeRole'])->name('users.changeRole');
    });
    // End Users //

    // Start Bidang Kepakaran //
    Route::group(['middleware' => 'role:Superadmin|Admin'], function () {
        Route::get('/bidang-kepakaran', [App\Http\Controllers\BidangKepakaranController::class, 'index'])->name('bidangKepakaran.index');
        Route::get('/bidang-kepakaran/create', [App\Http\Controllers\BidangKepakaranController::class, 'create'])->name('bidangKepakaran.create');
        Route::post('/bidang-kepakaran', [App\Http\Controllers\BidangKepakaranController::class, 'store'])->name('bidangKepakaran.store');
        Route::get('/bidang-kepakaran/{id}/edit', [App\Http\Controllers\BidangKepakaranController::class, 'edit'])->name('bidangKepakaran.edit');
        Route::put('/bidang-kepakaran/{id}', [App\Http\Controllers\BidangKepakaranController::class, 'update'])->name('bidangKepakaran.update');
        Route::delete('/bidang-kepakaran/{id}', [App\Http\Controllers\BidangKepakaranController::class, 'destroy'])->name('bidangKepakaran.destroy');
    });
    // End Bidang Kepakaran //

    // Start Kegiatan Mahasiswa //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi'], function () {
        Route::get('/kegiatan-mahasiswa', [App\Http\Controllers\KegiatanMahasiswaController::class, 'index'])->name('kegiatanMahasiswa.index');
        Route::get('/kegiatan-mahasiswa/create', [App\Http\Controllers\KegiatanMahasiswaController::class, 'create'])->name('kegiatanMahasiswa.create');
        Route::post('/kegiatan-mahasiswa', [App\Http\Controllers\KegiatanMahasiswaController::class, 'store'])->name('kegiatanMahasiswa.store');
        Route::get('/kegiatan-mahasiswa/{id}/edit', [App\Http\Controllers\KegiatanMahasiswaController::class, 'edit'])->name('kegiatanMahasiswa.edit');
        Route::put('/kegiatan-mahasiswa/{id}', [App\Http\Controllers\KegiatanMahasiswaController::class, 'update'])->name('kegiatanMahasiswa.update');
        Route::delete('/kegiatan-mahasiswa/{id}', [App\Http\Controllers\KegiatanMahasiswaController::class, 'destroy'])->name('kegiatanMahasiswa.destroy');
    });
    // End Kegiatan Mahasiswa //

    // Start Kegiatan //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi', 'prefix' => 'kegiatan', 'as' => 'kegiatan.'], function () {
        Route::get('/', [App\Http\Controllers\KegiatanController::class, 'index'])->name('index');
        Route::get('/{kegiatan_mahasiswa_id}/detail', [App\Http\Controllers\KegiatanController::class, 'show'])->name('show');
        Route::get('/get-mahasiswa-by-angkatan', [App\Http\Controllers\KegiatanController::class, 'getMahasiswaByAngkatan'])->name('getMahasiswaByAngkatan');
        Route::get('/{kegiatan_mahasiswa_id}', [App\Http\Controllers\KegiatanController::class, 'dataTables'])->name('dataTables');
        Route::post('/{kegiatan_mahasiswa_id}/tambah-peserta', [App\Http\Controllers\KegiatanController::class, 'store'])->name('store');
        Route::delete('/{id}', [App\Http\Controllers\KegiatanController::class, 'destroy'])->name('destroy');
    });
    // End Kegiatan //

    // Start Lampiran Kegiatan //
    Route::group(['prefix' => 'lampiran-kegiatan', 'as' => 'lampiranKegiatan.'], function () {
        Route::get('/{kegiatan_mahasiswa_slug}', [App\Http\Controllers\LampiranKegiatanController::class, 'index'])->name('index');
        Route::get('/{id}/detail', [App\Http\Controllers\LampiranKegiatanController::class, 'detail'])->name('detail');

        Route::group(['middleware' => 'role:Mahasiswa'], function () {
            Route::get('/{kegiatan_id}/datatables', [App\Http\Controllers\LampiranKegiatanController::class, 'dataTables'])->name('dataTables');
            Route::post('/{kegiatan_id}', [App\Http\Controllers\LampiranKegiatanController::class, 'store'])->name('store');
            Route::delete('/{id}', [App\Http\Controllers\LampiranKegiatanController::class, 'destroy'])->name('destroy');
        });

        Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi|Dosen'], function () {
            Route::get('/{kegiatan_mahasiswa_slug}/get-datatables', [App\Http\Controllers\LampiranKegiatanController::class, 'getDataTables'])->name('getDataTables'); 
            Route::get('/{kegiatan_id}/review', [App\Http\Controllers\LampiranKegiatanController::class, 'review'])->name('review');
            Route::post('/{lampiran_id}/setujui', [App\Http\Controllers\LampiranKegiatanController::class, 'setujui'])->name('setujui');
            Route::post('/{lampiran_id}/tolak', [App\Http\Controllers\LampiranKegiatanController::class, 'tolak'])->name('tolak');
            Route::post('/{lampiran_id}/batal', [App\Http\Controllers\LampiranKegiatanController::class, 'batal'])->name('batal');
        });
    });
    // End Lampiran Kegiatan //

    // Start Profil Program Studi //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
        // Start Sejarah //
        Route::get('/{prodi}/sejarah/', [App\Http\Controllers\ProfilProgramStudiController::class, 'sejarah'])->name('sejarah.index');
        Route::put('/{prodi}/sejarah/', [App\Http\Controllers\ProfilProgramStudiController::class, 'updateSejarah'])->name('sejarah.update');
        // End Sejarah //


        // Start Visi Keilmuan, Tujuan, dan Strategi //
        Route::get('/{prodi}/visi-keilmuan-tujuan-strategi', [App\Http\Controllers\ProfilProgramStudiController::class, 'visiKeilmuanTujuanStrategi'])->name('visiKeilmuanTujuanStrategi.index');
        Route::put('/{prodi}/visi-keilmuan-tujuan-strategi', [App\Http\Controllers\ProfilProgramStudiController::class, 'updateVisiKeilmuanTujuanStrategi'])->name('visiKeilmuanTujuanStrategi.update');
        // End Visi Keilmuan, Tujuan, dan Strategi //

        // Start Struktur Organisasi //
        Route::get('/{prodi}/struktur-organisasi', [App\Http\Controllers\ProfilProgramStudiController::class, 'strukturOrganisasi'])->name('strukturOrganisasi.index');
        Route::put('/{prodi}/struktur-organisasi', [App\Http\Controllers\ProfilProgramStudiController::class, 'updateStrukturOrganisasi'])->name('strukturOrganisasi.update');
        // End Struktur Organisasi //

        // Start Kontak dan Lokasi //
        Route::get('/{prodi}/kontak-lokasi', [App\Http\Controllers\ProfilProgramStudiController::class, 'kontakLokasi'])->name('kontakLokasi.index');
        Route::put('/{prodi}/kontak-lokasi', [App\Http\Controllers\ProfilProgramStudiController::class, 'updateKontakLokasi'])->name('kontakLokasi.update');
        // End Kontak dan Lokasi //

        // Start Video Profil //
        Route::get('/{prodi}/video-profil', [App\Http\Controllers\ProfilProgramStudiController::class, 'videoProfil'])->name('videoProfil.index');
        Route::put('/{prodi}/video-profil', [App\Http\Controllers\ProfilProgramStudiController::class, 'updateVideoProfil'])->name('videoProfil.update');
        // End Video Profil //
    });
    // End Profil Program Studi //

    // Start Kategori Sarana //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
        Route::get('/kategori-sarana', [App\Http\Controllers\SaranaKategoriController::class, 'index'])->name('kategoriSarana.index');
        Route::get('/kategori-sarana/create', [App\Http\Controllers\SaranaKategoriController::class, 'create'])->name('kategoriSarana.create');
        Route::post('/kategori-sarana', [App\Http\Controllers\SaranaKategoriController::class, 'store'])->name('kategoriSarana.store');
        Route::get('/kategori-sarana/{id}/edit', [App\Http\Controllers\SaranaKategoriController::class, 'edit'])->name('kategoriSarana.edit');
        Route::put('/kategori-sarana/{id}', [App\Http\Controllers\SaranaKategoriController::class, 'update'])->name('kategoriSarana.update');
        Route::delete('/kategori-sarana/{id}', [App\Http\Controllers\SaranaKategoriController::class, 'destroy'])->name('kategoriSarana.destroy');
    });
    // End Kategori Sarana //

    // Start Sarana //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
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
    });
    // End Sarana //

    // Start Kategori Prasarana //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
        Route::get('/kategori-prasarana', [App\Http\Controllers\PrasaranaKategoriController::class, 'index'])->name('kategoriPrasarana.index');
        Route::get('/kategori-prasarana/create', [App\Http\Controllers\PrasaranaKategoriController::class, 'create'])->name('kategoriPrasarana.create');
        Route::post('/kategori-prasarana', [App\Http\Controllers\PrasaranaKategoriController::class, 'store'])->name('kategoriPrasarana.store');
        Route::get('/kategori-prasarana/{id}/edit', [App\Http\Controllers\PrasaranaKategoriController::class, 'edit'])->name('kategoriPrasarana.edit');
        Route::put('/kategori-prasarana/{id}', [App\Http\Controllers\PrasaranaKategoriController::class, 'update'])->name('kategoriPrasarana.update');
        Route::delete('/kategori-prasarana/{id}', [App\Http\Controllers\PrasaranaKategoriController::class, 'destroy'])->name('kategoriPrasarana.destroy');
    });
    // End Kategori Prasarana //

    // Start Prasarana //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
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
    });
    // End Prasarana //

    // Start Sistem Informasi //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
        Route::get('/sistem-informasi', [App\Http\Controllers\SistemInformasiController::class, 'index'])->name('sistemInformasi.index');
        Route::get('/sistem-informasi/create', [App\Http\Controllers\SistemInformasiController::class, 'create'])->name('sistemInformasi.create');
        Route::post('/sistem-informasi', [App\Http\Controllers\SistemInformasiController::class, 'store'])->name('sistemInformasi.store');
        Route::get('/sistem-informasi/{id}/edit', [App\Http\Controllers\SistemInformasiController::class, 'edit'])->name('sistemInformasi.edit');
        Route::put('/sistem-informasi/{id}', [App\Http\Controllers\SistemInformasiController::class, 'update'])->name('sistemInformasi.update');
        Route::delete('/sistem-informasi/{id}', [App\Http\Controllers\SistemInformasiController::class, 'destroy'])->name('sistemInformasi.destroy');
    });
    // End Sistem Informasi //

    // Start Profil Lulusan //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
        Route::get('/profil-lulusan', [App\Http\Controllers\ProfilLulusanController::class, 'index'])->name('profilLulusan.index');
        Route::get('/profil-lulusan/create', [App\Http\Controllers\ProfilLulusanController::class, 'create'])->name('profilLulusan.create');
        Route::post('/profil-lulusan', [App\Http\Controllers\ProfilLulusanController::class, 'store'])->name('profilLulusan.store');
        Route::get('/profil-lulusan/{id}/edit', [App\Http\Controllers\ProfilLulusanController::class, 'edit'])->name('profilLulusan.edit');
        Route::put('/profil-lulusan/{id}', [App\Http\Controllers\ProfilLulusanController::class, 'update'])->name('profilLulusan.update');
        Route::delete('/profil-lulusan/{id}', [App\Http\Controllers\ProfilLulusanController::class, 'destroy'])->name('profilLulusan.destroy');
    });
    // End Profil Lulusan //

    // Start Capaian Pembelajaran //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
        Route::get('/capaian-pembelajaran', [App\Http\Controllers\CapaianPembelajaranController::class, 'index'])->name('capaianPembelajaran.index');
        Route::get('/capaian-pembelajaran/create', [App\Http\Controllers\CapaianPembelajaranController::class, 'create'])->name('capaianPembelajaran.create');
        Route::post('/capaian-pembelajaran', [App\Http\Controllers\CapaianPembelajaranController::class, 'store'])->name('capaianPembelajaran.store');
        Route::get('/capaian-pembelajaran/{id}/edit', [App\Http\Controllers\CapaianPembelajaranController::class, 'edit'])->name('capaianPembelajaran.edit');
        Route::put('/capaian-pembelajaran/{id}', [App\Http\Controllers\CapaianPembelajaranController::class, 'update'])->name('capaianPembelajaran.update');
        Route::delete('/capaian-pembelajaran/{id}', [App\Http\Controllers\CapaianPembelajaranController::class, 'destroy'])->name('capaianPembelajaran.destroy');
    });
    // End Capaian Pembelajaran //

    // Start Kurikulum //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
        Route::get('/kurikulum', [App\Http\Controllers\KurikulumController::class, 'index'])->name('kurikulum.index');
        Route::get('/kurikulum/create', [App\Http\Controllers\KurikulumController::class, 'create'])->name('kurikulum.create');
        Route::post('/kurikulum', [App\Http\Controllers\KurikulumController::class, 'store'])->name('kurikulum.store');
        Route::get('/kurikulum/{id}/edit', [App\Http\Controllers\KurikulumController::class, 'edit'])->name('kurikulum.edit');
        Route::put('/kurikulum/{id}', [App\Http\Controllers\KurikulumController::class, 'update'])->name('kurikulum.update');
        Route::delete('/kurikulum/{id}', [App\Http\Controllers\KurikulumController::class, 'destroy'])->name('kurikulum.destroy');
    });
    // End Kurikulum //

    // Start Dokumen Kurikulum //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
        Route::get('/dokumen-kurikulum', [App\Http\Controllers\DokumenKurikulumController::class, 'index'])->name('dokumenKurikulum.index');
        Route::post('/dokumen-kurikulum', [App\Http\Controllers\DokumenKurikulumController::class, 'store'])->name('dokumenKurikulum.store');
        Route::post('/dokumen-kurikulum/update-status', [App\Http\Controllers\DokumenKurikulumController::class, 'updateStatus'])->name('dokumenKurikulum.updateStatus');
        Route::delete('/dokumen-kurikulum/{id}', [App\Http\Controllers\DokumenKurikulumController::class, 'destroy'])->name('dokumenKurikulum.destroy');
    });
    // End Dokumen Kurikulum //

    // Start Kalender Akademik //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
        Route::get('/kalender-akademik', [App\Http\Controllers\KalenderAkademikController::class, 'index'])->name('kalenderAkademik.index');
        Route::group(['middleware' => ['role:Superadmin|Admin|Kajur']], function () {
            Route::get('/kalender-akademik/create', [App\Http\Controllers\KalenderAkademikController::class, 'create'])->name('kalenderAkademik.create');
            Route::post('/kalender-akademik', [App\Http\Controllers\KalenderAkademikController::class, 'store'])->name('kalenderAkademik.store');
            Route::get('/kalender-akademik/{id}/edit', [App\Http\Controllers\KalenderAkademikController::class, 'edit'])->name('kalenderAkademik.edit');
            Route::put('/kalender-akademik/{id}', [App\Http\Controllers\KalenderAkademikController::class, 'update'])->name('kalenderAkademik.update');
            Route::delete('/kalender-akademik/{id}', [App\Http\Controllers\KalenderAkademikController::class, 'destroy'])->name('kalenderAkademik.destroy');
        });
    });
    // End Kalender Akademik //

    // Start Kegiatan Perkuliahan //
    Route::group(['middleware' => ['role:Superadmin|Admin|Kajur|Kaprodi']], function () {
        Route::get('/kegiatan-perkuliahan', [App\Http\Controllers\KegiatanPerkuliahanController::class, 'index'])->name('kegiatanPerkuliahan.index');
        Route::get('/kegiatan-perkuliahan/create', [App\Http\Controllers\KegiatanPerkuliahanController::class, 'create'])->name('kegiatanPerkuliahan.create');
        Route::post('/kegiatan-perkuliahan/upload-image', [App\Http\Controllers\KegiatanPerkuliahanTemporaryImageController::class, 'uploadTemporaryImage'])->name('kegiatanPerkuliahan.uploadTemporaryImage');
        Route::get('/kegiatan-perkuliahan/get-image', [App\Http\Controllers\KegiatanPerkuliahanTemporaryImageController::class, 'getTemporaryImage'])->name('kegiatanPerkuliahan.getTemporaryImage');
        Route::delete('/kegiatan-perkuliahan/delete-image', [App\Http\Controllers\KegiatanPerkuliahanTemporaryImageController::class, 'deleteTemporaryImage'])->name('kegiatanPerkuliahan.deleteTemporaryImage');
        Route::get('/kegiatan-perkuliahan/{id}/detail-image', [App\Http\Controllers\KegiatanPerkuliahanImageController::class, 'detailImage'])->name('kegiatanPerkuliahan.detailImage');
        Route::delete('/kegiatan-perkuliahan/{id}/delete-image', [App\Http\Controllers\KegiatanPerkuliahanImageController::class, 'deleteImage'])->name('kegiatanPerkuliahan.deleteImage');
        Route::post('/kegiatan-perkuliahan', [App\Http\Controllers\KegiatanPerkuliahanController::class, 'store'])->name('kegiatanPerkuliahan.store');
        Route::get('/kegiatan-perkuliahan/{id}/edit', [App\Http\Controllers\KegiatanPerkuliahanController::class, 'edit'])->name('kegiatanPerkuliahan.edit');
        Route::put('/kegiatan-perkuliahan/{id}', [App\Http\Controllers\KegiatanPerkuliahanController::class, 'update'])->name('kegiatanPerkuliahan.update');
        Route::delete('/kegiatan-perkuliahan/{id}', [App\Http\Controllers\KegiatanPerkuliahanController::class, 'destroy'])->name('kegiatanPerkuliahan.destroy');
    });
    // End Kegiatan Perkuliahan //

    // Start Penelitian //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi|Dosen'], function () {
        Route::get('/penelitian', [App\Http\Controllers\PenelitianController::class, 'index'])->name('penelitian.index');
        Route::get('/penelitian/create', [App\Http\Controllers\PenelitianController::class, 'create'])->name('penelitian.create');
        Route::post('/penelitian', [App\Http\Controllers\PenelitianController::class, 'store'])->name('penelitian.store');
        Route::get('/penelitian/{id}/edit', [App\Http\Controllers\PenelitianController::class, 'edit'])->name('penelitian.edit');
        Route::put('/penelitian/{id}', [App\Http\Controllers\PenelitianController::class, 'update'])->name('penelitian.update');
        Route::delete('/penelitian/{id}', [App\Http\Controllers\PenelitianController::class, 'destroy'])->name('penelitian.destroy');
    });
    // End Penelitian //

    // Start Pengabdian Masyarakat //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi|Dosen'], function () {
        Route::get('/pengabdian-masyarakat', [App\Http\Controllers\PengabdianMasyarakatController::class, 'index'])->name('pengabdianMasyarakat.index');
        Route::get('/pengabdian-masyarakat/create', [App\Http\Controllers\PengabdianMasyarakatController::class, 'create'])->name('pengabdianMasyarakat.create');
        Route::post('/pengabdian-masyarakat', [App\Http\Controllers\PengabdianMasyarakatController::class, 'store'])->name('pengabdianMasyarakat.store');
        Route::get('/pengabdian-masyarakat/{id}/edit', [App\Http\Controllers\PengabdianMasyarakatController::class, 'edit'])->name('pengabdianMasyarakat.edit');
        Route::put('/pengabdian-masyarakat/{id}', [App\Http\Controllers\PengabdianMasyarakatController::class, 'update'])->name('pengabdianMasyarakat.update');
        Route::delete('/pengabdian-masyarakat/{id}', [App\Http\Controllers\PengabdianMasyarakatController::class, 'destroy'])->name('pengabdianMasyarakat.destroy');
    });
    // End Pengabdian Masyarakat //

    // Start Publikasi //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi|Dosen'], function () {
        Route::get('/publikasi', [App\Http\Controllers\PublikasiController::class, 'index'])->name('publikasi.index');
        Route::get('/publikasi/create', [App\Http\Controllers\PublikasiController::class, 'create'])->name('publikasi.create');
        Route::post('/publikasi', [App\Http\Controllers\PublikasiController::class, 'store'])->name('publikasi.store');
        Route::get('/publikasi/{id}/edit', [App\Http\Controllers\PublikasiController::class, 'edit'])->name('publikasi.edit');
        Route::put('/publikasi/{id}', [App\Http\Controllers\PublikasiController::class, 'update'])->name('publikasi.update');
        Route::delete('/publikasi/{id}', [App\Http\Controllers\PublikasiController::class, 'destroy'])->name('publikasi.destroy');
    });
    // End Publikasi //

    // Start Kerja Sama Dalam Negeri //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi|Dosen'], function () {
        Route::get('/kerja-sama-dalam-negeri', [App\Http\Controllers\KerjasamaDalamNegeriController::class, 'index'])->name('kerjasamaDalamNegeri.index');
        Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi'], function () {
            Route::get('/kerja-sama-dalam-negeri/create', [App\Http\Controllers\KerjasamaDalamNegeriController::class, 'create'])->name('kerjasamaDalamNegeri.create');
            Route::post('/kerja-sama-dalam-negeri', [App\Http\Controllers\KerjasamaDalamNegeriController::class, 'store'])->name('kerjasamaDalamNegeri.store');
            Route::get('/kerja-sama-dalam-negeri/{id}/edit', [App\Http\Controllers\KerjasamaDalamNegeriController::class, 'edit'])->name('kerjasamaDalamNegeri.edit');
            Route::put('/kerja-sama-dalam-negeri/{id}', [App\Http\Controllers\KerjasamaDalamNegeriController::class, 'update'])->name('kerjasamaDalamNegeri.update');
            Route::delete('/kerja-sama-dalam-negeri/{id}', [App\Http\Controllers\KerjasamaDalamNegeriController::class, 'destroy'])->name('kerjasamaDalamNegeri.destroy');
        });
    });
    // End Kerja Sama Dalam Negeri //

    // Start Kerja Sama Luar Negeri //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi|Dosen'], function () {
        Route::get('/kerja-sama-luar-negeri', [App\Http\Controllers\KerjasamaLuarNegeriController::class, 'index'])->name('kerjasamaLuarNegeri.index');
        Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi'], function () {
            Route::get('/kerja-sama-luar-negeri/create', [App\Http\Controllers\KerjasamaLuarNegeriController::class, 'create'])->name('kerjasamaLuarNegeri.create');
            Route::post('/kerja-sama-luar-negeri', [App\Http\Controllers\KerjasamaLuarNegeriController::class, 'store'])->name('kerjasamaLuarNegeri.store');
            Route::get('/kerja-sama-luar-negeri/{id}/edit', [App\Http\Controllers\KerjasamaLuarNegeriController::class, 'edit'])->name('kerjasamaLuarNegeri.edit');
            Route::put('/kerja-sama-luar-negeri/{id}', [App\Http\Controllers\KerjasamaLuarNegeriController::class, 'update'])->name('kerjasamaLuarNegeri.update');
            Route::delete('/kerja-sama-luar-negeri/{id}', [App\Http\Controllers\KerjasamaLuarNegeriController::class, 'destroy'])->name('kerjasamaLuarNegeri.destroy');
        });
    });
    // End Kerja Sama Luar Negeri //

    // Start Pendaftaran Mahasiswa Baru //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur'], function () {
        Route::get('/pendaftaran-mahasiswa-baru', [App\Http\Controllers\PendaftaranMahasiswaBaruController::class, 'index'])->name('pendaftaranMahasiswaBaru.index');
        Route::get('/pendaftaran-mahasiswa-baru/create', [App\Http\Controllers\PendaftaranMahasiswaBaruController::class, 'create'])->name('pendaftaranMahasiswaBaru.create');
        Route::post('/pendaftaran-mahasiswa-baru', [App\Http\Controllers\PendaftaranMahasiswaBaruController::class, 'store'])->name('pendaftaranMahasiswaBaru.store');
        Route::get('/pendaftaran-mahasiswa-baru/{id}/edit', [App\Http\Controllers\PendaftaranMahasiswaBaruController::class, 'edit'])->name('pendaftaranMahasiswaBaru.edit');
        Route::put('/pendaftaran-mahasiswa-baru/{id}', [App\Http\Controllers\PendaftaranMahasiswaBaruController::class, 'update'])->name('pendaftaranMahasiswaBaru.update');
        Route::delete('/pendaftaran-mahasiswa-baru/{id}', [App\Http\Controllers\PendaftaranMahasiswaBaruController::class, 'destroy'])->name('pendaftaranMahasiswaBaru.destroy');
    });
    // End Pendaftaran Mahasiswa Baru //
    
    // Start Prestasi Mahasiswa //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi|Dosen'], function () {
        Route::get('/prestasi-mahasiswa', [App\Http\Controllers\PrestasiMahasiswaController::class, 'index'])->name('prestasiMahasiswa.index');
        
        Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi'], function () {
            Route::get('/prestasi-mahasiswa/create', [App\Http\Controllers\PrestasiMahasiswaController::class, 'create'])->name('prestasiMahasiswa.create');
            Route::post('/prestasi-mahasiswa', [App\Http\Controllers\PrestasiMahasiswaController::class, 'store'])->name('prestasiMahasiswa.store');
            Route::get('/prestasi-mahasiswa/{id}/edit', [App\Http\Controllers\PrestasiMahasiswaController::class, 'edit'])->name('prestasiMahasiswa.edit');
            Route::put('/prestasi-mahasiswa/{id}', [App\Http\Controllers\PrestasiMahasiswaController::class, 'update'])->name('prestasiMahasiswa.update');
            Route::delete('/prestasi-mahasiswa/{id}', [App\Http\Controllers\PrestasiMahasiswaController::class, 'destroy'])->name('prestasiMahasiswa.destroy');
        });
    });
    // End Prestasi Mahasiswa //

    // Start Peluang Mahasiswa //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi|Dosen'], function () {
        // Start Beasiswa //
        Route::get('/beasiswa', [App\Http\Controllers\BeasiswaController::class, 'index'])->name('beasiswa.index');
        Route::get('/beasiswa/create', [App\Http\Controllers\BeasiswaController::class, 'create'])->name('beasiswa.create');
        Route::post('/beasiswa', [App\Http\Controllers\BeasiswaController::class, 'store'])->name('beasiswa.store');
        Route::get('/beasiswa/{id}/edit', [App\Http\Controllers\BeasiswaController::class, 'edit'])->name('beasiswa.edit');
        Route::put('/beasiswa/{id}', [App\Http\Controllers\BeasiswaController::class, 'update'])->name('beasiswa.update');
        Route::delete('/beasiswa/{id}', [App\Http\Controllers\BeasiswaController::class, 'destroy'])->name('beasiswa.destroy');
        // End Beasiswa //

        // Start Exchange dan Double Degree //
        Route::get('/exchange-dan-double-degree', [App\Http\Controllers\ExchangeDanDoubleDegreeController::class, 'index'])->name('edd.index');
        Route::get('/exchange-dan-double-degree/create', [App\Http\Controllers\ExchangeDanDoubleDegreeController::class, 'create'])->name('edd.create');
        Route::post('/exchange-dan-double-degree', [App\Http\Controllers\ExchangeDanDoubleDegreeController::class, 'store'])->name('edd.store');
        Route::get('/exchange-dan-double-degree/{id}/edit', [App\Http\Controllers\ExchangeDanDoubleDegreeController::class, 'edit'])->name('edd.edit');
        Route::put('/exchange-dan-double-degree/{id}', [App\Http\Controllers\ExchangeDanDoubleDegreeController::class, 'update'])->name('edd.update');
        Route::delete('/exchange-dan-double-degree/{id}', [App\Http\Controllers\ExchangeDanDoubleDegreeController::class, 'destroy'])->name('edd.destroy');
        // End Exchange dan Double Degree //

        // Start Seminar dan Kompetisi //
        Route::get('/seminar-dan-kompetisi', [App\Http\Controllers\SeminarDanKompetisiController::class, 'index'])->name('seminarDanKompetisi.index');
        Route::get('/seminar-dan-kompetisi/create', [App\Http\Controllers\SeminarDanKompetisiController::class, 'create'])->name('seminarDanKompetisi.create');
        Route::post('/seminar-dan-kompetisi', [App\Http\Controllers\SeminarDanKompetisiController::class, 'store'])->name('seminarDanKompetisi.store');
        Route::get('/seminar-dan-kompetisi/{id}/edit', [App\Http\Controllers\SeminarDanKompetisiController::class, 'edit'])->name('seminarDanKompetisi.edit');
        Route::put('/seminar-dan-kompetisi/{id}', [App\Http\Controllers\SeminarDanKompetisiController::class, 'update'])->name('seminarDanKompetisi.update');
        Route::delete('/seminar-dan-kompetisi/{id}', [App\Http\Controllers\SeminarDanKompetisiController::class, 'destroy'])->name('seminarDanKompetisi.destroy');
        // End Seminar dan Kompetisi //

        // Start Magang atau Praktik Industri //
        Route::get('/magang-atau-praktik-industri', [App\Http\Controllers\MagangAtauPraktikIndustriController::class, 'index'])->name('mapi.index');
        Route::get('/magang-atau-praktik-industri/create', [App\Http\Controllers\MagangAtauPraktikIndustriController::class, 'create'])->name('mapi.create');
        Route::post('/magang-atau-praktik-industri', [App\Http\Controllers\MagangAtauPraktikIndustriController::class, 'store'])->name('mapi.store');
        Route::get('/magang-atau-praktik-industri/{id}/edit', [App\Http\Controllers\MagangAtauPraktikIndustriController::class, 'edit'])->name('mapi.edit');
        Route::put('/magang-atau-praktik-industri/{id}', [App\Http\Controllers\MagangAtauPraktikIndustriController::class, 'update'])->name('mapi.update');
        Route::delete('/magang-atau-praktik-industri/{id}', [App\Http\Controllers\MagangAtauPraktikIndustriController::class, 'destroy'])->name('mapi.destroy');
        // End Magang atau Praktik Industri //

        // Start Lowongan Kerja //
        Route::get('/lowongan-kerja', [App\Http\Controllers\LowonganKerjaController::class, 'index'])->name('lowonganKerja.index');
        Route::get('/lowongan-kerja/create', [App\Http\Controllers\LowonganKerjaController::class, 'create'])->name('lowonganKerja.create');
        Route::post('/lowongan-kerja', [App\Http\Controllers\LowonganKerjaController::class, 'store'])->name('lowonganKerja.store');
        Route::get('/lowongan-kerja/{id}/edit', [App\Http\Controllers\LowonganKerjaController::class, 'edit'])->name('lowonganKerja.edit');
        Route::put('/lowongan-kerja/{id}', [App\Http\Controllers\LowonganKerjaController::class, 'update'])->name('lowonganKerja.update');
        Route::delete('/lowongan-kerja/{id}', [App\Http\Controllers\LowonganKerjaController::class, 'destroy'])->name('lowonganKerja.destroy');
        // End Lowongan Kerja //
    });
    // End Peluang Mahasiswa //

    // Start Organisasi Mahasiswa //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi'], function () {
        Route::get('/organisasi-mahasiswa', [App\Http\Controllers\OrganisasiMahasiswaController::class, 'index'])->name('organisasiMahasiswa.index');
        Route::get('/organisasi-mahasiswa/create', [App\Http\Controllers\OrganisasiMahasiswaController::class, 'create'])->name('organisasiMahasiswa.create');
        Route::post('/organisasi-mahasiswa', [App\Http\Controllers\OrganisasiMahasiswaController::class, 'store'])->name('organisasiMahasiswa.store');
        Route::get('/organisasi-mahasiswa/{id}/edit', [App\Http\Controllers\OrganisasiMahasiswaController::class, 'edit'])->name('organisasiMahasiswa.edit');
        Route::put('/organisasi-mahasiswa/{id}', [App\Http\Controllers\OrganisasiMahasiswaController::class, 'update'])->name('organisasiMahasiswa.update');
        Route::delete('/organisasi-mahasiswa/{id}', [App\Http\Controllers\OrganisasiMahasiswaController::class, 'destroy'])->name('organisasiMahasiswa.destroy');
    });
    // End Organisasi Mahasiswa //

    // Start Repositori //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi|Dosen'], function () {
        // Start Dokumen Kebijakan //
        Route::get('/dokumen-kebijakan', [App\Http\Controllers\DokumenKebijakanController::class, 'index'])->name('dokumenKebijakan.index');
        Route::get('/dokumen-kebijakan/create', [App\Http\Controllers\DokumenKebijakanController::class, 'create'])->name('dokumenKebijakan.create');
        Route::post('/dokumen-kebijakan', [App\Http\Controllers\DokumenKebijakanController::class, 'store'])->name('dokumenKebijakan.store');
        Route::get('/dokumen-kebijakan/{id}/edit', [App\Http\Controllers\DokumenKebijakanController::class, 'edit'])->name('dokumenKebijakan.edit');
        Route::put('/dokumen-kebijakan/{id}', [App\Http\Controllers\DokumenKebijakanController::class, 'update'])->name('dokumenKebijakan.update');
        Route::delete('/dokumen-kebijakan/{id}', [App\Http\Controllers\DokumenKebijakanController::class, 'destroy'])->name('dokumenKebijakan.destroy');
        // End Dokumen Kebijakan //

        // Start Dokumen Lainnya //
        Route::get('/dokumen-lainnya', [App\Http\Controllers\DokumenLainnyaController::class, 'index'])->name('dokumenLainnya.index');
        Route::get('/dokumen-lainnya/create', [App\Http\Controllers\DokumenLainnyaController::class, 'create'])->name('dokumenLainnya.create');
        Route::post('/dokumen-lainnya', [App\Http\Controllers\DokumenLainnyaController::class, 'store'])->name('dokumenLainnya.store');
        Route::get('/dokumen-lainnya/{id}/edit', [App\Http\Controllers\DokumenLainnyaController::class, 'edit'])->name('dokumenLainnya.edit');
        Route::put('/dokumen-lainnya/{id}', [App\Http\Controllers\DokumenLainnyaController::class, 'update'])->name('dokumenLainnya.update');
        Route::delete('/dokumen-lainnya/{id}', [App\Http\Controllers\DokumenLainnyaController::class, 'destroy'])->name('dokumenLainnya.destroy');
        // End Dokumen Lainnya //

        // Start Data Dukung Akreditasi //
        Route::get('/data-dukung-akreditasi', [App\Http\Controllers\DataDukungAkreditasiController::class, 'index'])->name('dataDukungAkreditasi.index');
        Route::get('/data-dukung-akreditasi/create', [App\Http\Controllers\DataDukungAkreditasiController::class, 'create'])->name('dataDukungAkreditasi.create');
        Route::post('/data-dukung-akreditasi', [App\Http\Controllers\DataDukungAkreditasiController::class, 'store'])->name('dataDukungAkreditasi.store');
        Route::get('/data-dukung-akreditasi/{id}/edit', [App\Http\Controllers\DataDukungAkreditasiController::class, 'edit'])->name('dataDukungAkreditasi.edit');
        Route::put('/data-dukung-akreditasi/{id}', [App\Http\Controllers\DataDukungAkreditasiController::class, 'update'])->name('dataDukungAkreditasi.update');
        Route::delete('/data-dukung-akreditasi/{id}', [App\Http\Controllers\DataDukungAkreditasiController::class, 'destroy'])->name('dataDukungAkreditasi.destroy');
        // End Data Dukung Akreditasi //
    });
    // End Repositori //

    // Start Banner //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi'], function () {
        Route::get('/banner', [App\Http\Controllers\BannerController::class, 'index'])->name('banner.index');
        Route::post('/banner', [App\Http\Controllers\BannerController::class, 'store'])->name('banner.store');
        Route::delete('/banner/{id}', [App\Http\Controllers\BannerController::class, 'destroy'])->name('banner.destroy');
    });
    // End Banner //

    // Start Berita //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi|Dosen'], function () {
        Route::get('/berita', [App\Http\Controllers\BeritaController::class, 'index'])->name('berita.index');
        Route::get('/berita/create', [App\Http\Controllers\BeritaController::class, 'create'])->name('berita.create');
        Route::post('/berita', [App\Http\Controllers\BeritaController::class, 'store'])->name('berita.store');
        Route::get('/berita/{id}/edit', [App\Http\Controllers\BeritaController::class, 'edit'])->name('berita.edit');
        Route::put('/berita/{id}', [App\Http\Controllers\BeritaController::class, 'update'])->name('berita.update');
        Route::delete('/berita/{id}', [App\Http\Controllers\BeritaController::class, 'destroy'])->name('berita.destroy');
    });
    // End Berita //

    // Start Agenda //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur|Kaprodi'], function () {
        Route::get('/agenda', [App\Http\Controllers\AgendaCOntroller::class, 'index'])->name('agenda.index');
        Route::get('/agenda/create', [App\Http\Controllers\AgendaCOntroller::class, 'create'])->name('agenda.create');
        Route::post('/agenda', [App\Http\Controllers\AgendaCOntroller::class, 'store'])->name('agenda.store');
        Route::get('/agenda/{id}/edit', [App\Http\Controllers\AgendaCOntroller::class, 'edit'])->name('agenda.edit');
        Route::put('/agenda/{id}', [App\Http\Controllers\AgendaCOntroller::class, 'update'])->name('agenda.update');
        Route::delete('/agenda/{id}', [App\Http\Controllers\AgendaCOntroller::class, 'destroy'])->name('agenda.destroy');
    });
    // End Agenda //

    // Start Jurnal //
    Route::group(['middleware' => 'role:Superadmin|Admin|Kajur'], function () {
        Route::get('/jurnal', [App\Http\Controllers\JurnalController::class, 'index'])->name('jurnal.index');
        Route::post('/jurnal', [App\Http\Controllers\JurnalController::class, 'store'])->name('jurnal.store');
        Route::delete('/jurnal/{id}', [App\Http\Controllers\JurnalController::class, 'destroy'])->name('jurnal.destroy');
    });
    // End Jurnal //

    // Start Pengaturan Akun //
    Route::get('/pengaturan-akun', [App\Http\Controllers\PengaturanController::class, 'pengaturanAkun'])->name('pengaturanAkun');
    Route::put('/pengaturan-akun', [App\Http\Controllers\PengaturanController::class, 'updateInformasiAkun'])->name('informasiAkun.update');
    Route::put('/pengaturan-akun/password', [App\Http\Controllers\PengaturanController::class, 'updatePasswordAkun'])->name('passwordAkun.update');
    // End Pengaturan Akun //

    // Start Pengaturan Sistem //
    Route::group(['middleware' => 'role:Superadmin'], function () {
        Route::get('/pengaturan-sistem', [App\Http\Controllers\PengaturanController::class, 'pengaturanSistem'])->name('pengaturanSistem');
        Route::put('/pengaturan-sistem', [App\Http\Controllers\PengaturanController::class, 'updatePengaturanSistem'])->name('pengaturanSistem.update');
        Route::put('/ubah-logo', [App\Http\Controllers\PengaturanController::class, 'updateLogo'])->name('pengaturanSistem.updateLogo');
        Route::post('/setel-ke-pengaturan-pabrik', [App\Http\Controllers\PengaturanController::class, 'resetToFactory'])->name('pengaturanSistem.resetToFactory');
    });
    // End Pengaturan Sistem //

    Route::group(['prefix' => 'notifikasi', 'as' => 'notifikasi.'], function () {
        Route::get('/', [App\Http\Controllers\NotifikasiController::class, 'showAll'])->name('index');
        Route::get('/jumlah', [App\Http\Controllers\NotifikasiController::class, 'jumlah'])->name('jumlah');
        Route::post('/baca', [App\Http\Controllers\NotifikasiController::class, 'dibaca'])->name('dibaca');
        Route::delete('/delete', [App\Http\Controllers\NotifikasiController::class, 'destroy'])->name('delete');
    });
});
