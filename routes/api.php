<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PegawaiController;
use App\Models\Pegawai;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'user'
], function() {
    Route::post('register', [UserController::class, 'register']);                               // Register user
    Route::post('login', [UserController::class, 'login']);                                     // Login user
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::post('logout', [UserController::class, 'logout']);                               // Logout user
        Route::post('refresh', [UserController::class, 'refresh']);                             // Refresh user auth (300 minutes)
        Route::get('data', [UserController::class, 'data']);                                    // Get user data
    });
});

Route::group([
    'prefix' => 'pns',
    'middleware' => 'auth:api'
], function() {
    Route::get('search/{query}', [PegawaiController::class, 'searchPegawai']);                  // Pencarian data pegawai
    Route::get('all', [PegawaiController::class, 'getAllPegawai']);                             // Melihat daftar pegawai
    Route::get('unit_kerja/{idUnitKerja}', [PegawaiController::class, 'getByUnitKerja']);       // Melihat daftar pegawai berdasarkan unit kerja
    Route::get('detail/{idPegawai}', [PegawaiController::class, 'getPegawaiDetail']);           // Melihat detail seorang pegawai

    Route::get('formData', [PegawaiController::class, 'getFormData']);                          // Penyedia data untuk form pegawai
    Route::post('store', [PegawaiController::class, 'storeNewPegawai']);                        // Aksi tambah pegawai baru
    Route::put('update', [PegawaiController::class, 'updatePegawai']);                          // Aksi simpan perubahan pegawai
    Route::delete('delete/{idPegawai}', [PegawaiController::class, 'deletePegawai']);           // Aksi hapus pegawai
    
    Route::group([
        'prefix' => 'photo'
    ], function() {
        Route::post('upload', [PegawaiController::class, 'uploadPhoto']);                       // Upload foto pegawai
        Route::get('download/{idPegawai}', [PegawaiController::class, 'downloadPhoto']);        // Download atau Get foto pegawai
        Route::delete('delete/{idPegawai}', [PegawaiController::class, 'deletePhoto']);         // Hapus foto pegawai
    });

    Route::get('exportExcel', [PegawaiController::class, 'exportExcelDataPegawai']);            // Download list pegawai dalam bentuk excel
});