<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TilangController;
use App\Http\Controllers\PortalController;

Route::get('/', [PortalController::class, 'index'])->name('portal.index');
Route::post('/portal/search', [PortalController::class, 'searchSiswa'])->name('portal.search');
Route::get('/portal/siswa/{id}', [PortalController::class, 'getDetailSiswa'])->name('portal.detail');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Route Profil Guru (Untuk ubah password dll)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route Kiosk (Mesin Pemindai E-Tilang)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/kiosk', [TilangController::class, 'index'])->name('kiosk.index');
    Route::post('/kiosk/search', [TilangController::class, 'searchSiswa'])->name('kiosk.search');
    Route::post('/kiosk/store', [TilangController::class, 'store'])->name('kiosk.store');
});

// Memuat route bawaan Breeze untuk Login, Logout, dan Lupa Password
require __DIR__.'/auth.php';