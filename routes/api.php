<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WalasPelanggaranController;
use App\Http\Controllers\Api\WalaskehadiranController;

/*
|--------------------------------------------------------------------------
| API Routes for Walas -> Kesiswaan Integration
|--------------------------------------------------------------------------
*/

Route::prefix('v1/walas')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | SECTION A: Pelanggaran (Violations)
    |--------------------------------------------------------------------------
    */
    Route::prefix('pelanggaran')->controller(WalasPelanggaranController::class)->group(function () {
        Route::get('/', 'index')                           ->name('walas.pelanggaran.index');
        Route::get('/stats', 'stats')                      ->name('walas.pelanggaran.stats');
        Route::get('/{id}', 'show')                        ->name('walas.pelanggaran.show');
        Route::get('/student/{studentId}', 'byStudent')    ->name('walas.pelanggaran.byStudent');
        Route::get('/walas/{walasId}', 'byWalas')          ->name('walas.pelanggaran.byWalas');
        Route::post('/sync', 'sync')                       ->name('walas.pelanggaran.sync');
    });

    /*
    |--------------------------------------------------------------------------
    | SECTION B: Kehadiran (Attendance)
    |--------------------------------------------------------------------------
    */
    Route::prefix('kehadiran')->controller(WalaskehadiranController::class)->group(function () {
        Route::get('/', 'index')                           ->name('walas.kehadiran.index');
        Route::get('/{id}', 'show')                        ->name('walas.kehadiran.show');
        Route::get('/student/{studentId}', 'byStudent')    ->name('walas.kehadiran.byStudent');
        Route::get('/kelas/{kelas}', 'byKelas')            ->name('walas.kehadiran.byKelas');
        Route::get('/walas/{walasId}', 'byWalas')          ->name('walas.kehadiran.byWalas');
    });
});

/*
|--------------------------------------------------------------------------
| Default Laravel API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
