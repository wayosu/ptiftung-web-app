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

// Data User
// Route::group(['middleware' => 'auth', 'prefix' => 'v1'], function () {
//     Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index'])->name('users.index.api');
//     Route::post('/users', [App\Http\Controllers\Api\UserController::class, 'store'])->name('users.store.api');
// });
