<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes — Multi-Guard (admin + pelanggan)
|--------------------------------------------------------------------------
|
| Semua route autentikasi menggunakan guard 'admin' dan 'pelanggan'.
| Tidak ada Login Admin terpisah dan Login Pelanggan terpisah.
| Satu halaman Login menangani kedua jenis pengguna secara otomatis.
|
*/

// ── Guest Routes ──────────────────────────────────────────────────────────
// Hanya dapat diakses jika belum login (baik sebagai admin maupun pelanggan)
Route::middleware('guest:admin,pelanggan')->group(function () {

    // Halaman Register — hanya untuk Pelanggan
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    // Halaman Login — satu halaman untuk Admin dan Pelanggan
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

});

// ── Password Reset Routes (Accessible for both Guest and Authenticated) ──
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');

// ── Authenticated Routes ──────────────────────────────────────────────────
// Hanya dapat diakses jika sudah login (sebagai admin atau pelanggan)
Route::middleware('auth:admin,pelanggan')->group(function () {

    // Logout — menghapus session guard yang aktif
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
