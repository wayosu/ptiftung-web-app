<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Route
Route::group(['middleware' => 'api.token', 'prefix' => 'v1'], function () {
    Route::get('/sarana/search', [App\Http\Controllers\FrontPage\ProfilController::class, 'saranaSearch']);
    Route::get('/sarana/{slug}/images', [App\Http\Controllers\FrontPage\ProfilController::class, 'saranaImages']);
    Route::get('/prasarana/search', [App\Http\Controllers\FrontPage\ProfilController::class, 'prasaranaSearch']);
    Route::get('/prasarana/{slug}/images', [App\Http\Controllers\FrontPage\ProfilController::class, 'prasaranaImages']);

    Route::get('/profil-lulusan', [App\Http\Controllers\FrontPage\AkademikController::class, 'getAPIProfilLulusan']);
});

// Data User
// Route::group(['middleware' => 'auth', 'prefix' => 'v1'], function () {
//     Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index'])->name('users.index.api');
//     Route::post('/users', [App\Http\Controllers\Api\UserController::class, 'store'])->name('users.store.api');
// });
