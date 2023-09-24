<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthKaryawanController;
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

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::get('auth/profile', [AuthController::class, 'profile']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});

Route::post('karyawan/register', [AuthKaryawanController::class, 'register']);
Route::post('karyawan/login', [AuthKaryawanController::class, 'login']);

Route::middleware(['auth:karyawan-api'])->group(function () {
    Route::post('karyawan/refresh', [AuthKaryawanController::class, 'refresh']);
    Route::get('karyawan/profile', [AuthKaryawanController::class, 'profile']);
    Route::post('karyawan/logout', [AuthKaryawanController::class, 'logout']);
});
