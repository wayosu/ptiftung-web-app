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
    Route::get('/usersAjax', [App\Http\Controllers\UserController::class, 'indexAjax'])->name('users.indexAjax');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    // By Role Admin / Dosen / Mahasiswa
    Route::get('/users/{role}', [App\Http\Controllers\UserController::class, 'byRole'])->name('users.byRole');
    Route::get('/byRoleAjax/{role}', [App\Http\Controllers\UserController::class, 'byRoleAjax'])->name('users.byRoleAjax');
    Route::post('/users/{role}/store', [App\Http\Controllers\UserController::class, 'byRoleStore'])->name('users.byRoleStore');
    Route::get('/users/{id}/{role}', [App\Http\Controllers\UserController::class, 'byRoleShow'])->name('users.byRoleShow');
    Route::get('/users/{id}/{role}/edit', [App\Http\Controllers\UserController::class, 'byRoleEdit'])->name('users.byRoleEdit');
    Route::put('/users/{id}/{role}', [App\Http\Controllers\UserController::class, 'byRoleUpdate'])->name('users.byRoleUpdate');

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
