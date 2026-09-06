<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Welcome Page ──────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ── Customer Routes ───────────────────────────────────────────────────────
// Guard: pelanggan — hanya pelanggan yang sudah login yang bisa mengakses
Route::middleware('auth:pelanggan')->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/jadwal', [\App\Http\Controllers\Customer\JadwalController::class, 'index'])->name('jadwal');

    Route::get('/booking', [\App\Http\Controllers\Customer\BookingController::class, 'index'])->name('booking');
    Route::get('/booking/slots/{tanggal}', [\App\Http\Controllers\Customer\BookingController::class, 'getSlots'])->name('booking.slots');
    Route::post('/booking', [\App\Http\Controllers\Customer\BookingController::class, 'store'])->name('booking.store');

    Route::get('/status', [\App\Http\Controllers\Customer\StatusController::class, 'index'])->name('status');
    Route::patch('/status/{id}/cancel', [\App\Http\Controllers\Customer\StatusController::class, 'cancel'])->name('status.cancel');
    Route::get('/riwayat', [\App\Http\Controllers\Customer\RiwayatController::class, 'index'])->name('riwayat');

    Route::get('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
});

// ── Admin Routes ──────────────────────────────────────────────────────────
// Guard: admin — hanya admin yang sudah login yang bisa mengakses
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('jadwal');
    Route::put('/jadwal', [\App\Http\Controllers\Admin\JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{hari}/reset', [\App\Http\Controllers\Admin\JadwalController::class, 'reset'])->name('jadwal.reset');

    Route::get('/kuota', [\App\Http\Controllers\Admin\KuotaController::class, 'index'])->name('kuota');
    Route::post('/kuota/generate', [\App\Http\Controllers\Admin\KuotaController::class, 'generate'])->name('kuota.generate');
    Route::get('/kuota/{tanggal}', [\App\Http\Controllers\Admin\KuotaController::class, 'getSlots'])->name('kuota.getSlots');
    Route::patch('/kuota/{id}/toggle', [\App\Http\Controllers\Admin\KuotaController::class, 'toggle'])->name('kuota.toggle');
    Route::patch('/kuota/{id}/reset', [\App\Http\Controllers\Admin\KuotaController::class, 'reset'])->name('kuota.reset');

    Route::get('/booking', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('booking');

    Route::get('/pengguna', [\App\Http\Controllers\Admin\PenggunaController::class, 'index'])->name('pengguna');

    Route::get('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');
});

// ── Profile Routes (Pelanggan) ────────────────────────────────────────────
Route::middleware('auth:pelanggan')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
