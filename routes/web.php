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
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    // Admin //
    Route::get('/users/admin', [App\Http\Controllers\UserController::class, 'byAdmin'])->name('users.byAdmin');
    Route::get('/users/admin/create', [App\Http\Controllers\UserController::class, 'createAdmin'])->name('users.createAdmin');
    Route::post('/users/admin', [App\Http\Controllers\UserController::class, 'storeAdmin'])->name('users.storeAdmin');
    Route::get('/users/admin/{id}/edit', [App\Http\Controllers\UserController::class, 'editAdmin'])->name('users.editAdmin');
    Route::put('/users/admin/{id}', [App\Http\Controllers\UserController::class, 'updateAdmin'])->name('users.updateAdmin');

    // Dosen //
    Route::get('/users/dosen', [App\Http\Controllers\UserController::class, 'byDosen'])->name('users.byDosen');

    // Mahasiswa //
    Route::get('/users/mahasiswa', [App\Http\Controllers\UserController::class, 'byMahasiswa'])->name('users.byMahasiswa');
    Route::get('/users/mahasiswa/create', [App\Http\Controllers\UserController::class, 'createMahasiswa'])->name('users.createMahasiswa');
    Route::post('/users/mahasiswa', [App\Http\Controllers\UserController::class, 'storeMahasiswa'])->name('users.storeMahasiswa');
    Route::get('/users/mahasiswa/{id}/edit', [App\Http\Controllers\UserController::class, 'editMahasiswa'])->name('users.editMahasiswa');
    Route::put('/users/mahasiswa/{id}', [App\Http\Controllers\UserController::class, 'updateMahasiswa'])->name('users.updateMahasiswa');

    // End Users //

    // Start Bidang Kepakaran //
    Route::get('/bidang-kepakaran', [App\Http\Controllers\BidangKepakaranController::class, 'index'])->name('bidangKepakaran.index');
    Route::get('/bidangKepakaranAjax', [App\Http\Controllers\BidangKepakaranController::class, 'indexAjax'])->name('bidangKepakaran.indexAjax');
    Route::post('/bidang-kepakaran', [App\Http\Controllers\BidangKepakaranController::class, 'store'])->name('bidangKepakaran.store');
    Route::get('/bidang-kepakaran/{id}/edit', [App\Http\Controllers\BidangKepakaranController::class, 'edit'])->name('bidangKepakaran.edit');
    Route::put('/bidang-kepakaran/{id}', [App\Http\Controllers\BidangKepakaranController::class, 'update'])->name('bidangKepakaran.update');
    Route::delete('/bidang-kepakaran/{id}', [App\Http\Controllers\BidangKepakaranController::class, 'destroy'])->name('bidangKepakaran.destroy');
    // End Bidang Kepakaran //
});
