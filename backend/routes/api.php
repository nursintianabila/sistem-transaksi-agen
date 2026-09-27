<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\TransaksiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


// ======================================================
// PUBLIC
// ======================================================

// Login dapat diakses tanpa token
Route::post('/login', [AuthController::class, 'login']);


// ======================================================
// ADMIN + OWNER
// ======================================================

Route::middleware('auth:sanctum')->group(function () {

    // Data user yang sedang login
    Route::get('/user', [AuthController::class, 'user']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);


    // ==================================================
    // READ TRANSAKSI
    // ADMIN + OWNER BOLEH
    // ==================================================

    Route::get(
        '/transaksi',
        [TransaksiController::class, 'index']
    );

    Route::get(
        '/transaksi/{transaksi}',
        [TransaksiController::class, 'show']
    );


    // ==================================================
    // CREATE, UPDATE, DELETE
    // ADMIN SAJA
    // ==================================================

    Route::middleware('admin')->group(function () {

        // Tambah transaksi
        Route::post(
            '/transaksi',
            [TransaksiController::class, 'store']
        );

        // Update transaksi
        Route::put(
            '/transaksi/{transaksi}',
            [TransaksiController::class, 'update']
        );

        Route::patch(
            '/transaksi/{transaksi}',
            [TransaksiController::class, 'update']
        );

        // Hapus transaksi
        Route::delete(
            '/transaksi/{transaksi}',
            [TransaksiController::class, 'destroy']
        );
    });
});