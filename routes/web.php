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
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth', 'prefix' => 'dashboard'], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

    // Start Users //
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
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
});
