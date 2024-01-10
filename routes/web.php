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

    // Start Users //
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/usersAjax', [App\Http\Controllers\UserController::class, 'indexAjax'])->name('users.indexAjax');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    // Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    // By Role
    Route::get('/users/{role}', [App\Http\Controllers\UserController::class, 'byRole'])->name('users.byRole');
    Route::get('/byRoleAjax/{role}', [App\Http\Controllers\UserController::class, 'byRoleAjax'])->name('users.byRoleAjax');

    // By Role Admin
    Route::get('/users/{id}/admin', [App\Http\Controllers\UserController::class, 'byRoleAdminShow'])->name('users.byRoleAdminShow');
    Route::put('/users/{id}/admin', [App\Http\Controllers\UserController::class, 'byRoleAdminUpdate'])->name('users.byRoleAdminUpdate');
    // End Users //
});
