<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TilangController;
use App\Http\Controllers\PortalController;
use App\Models\Siswa;
use App\Models\RiwayatPelanggaran;
use Illuminate\Http\Request;

Route::get('/', [PortalController::class, 'index'])->name('portal.index');
Route::post('/portal/search', [PortalController::class, 'searchSiswa'])->name('portal.search');
Route::get('/portal/siswa/{id}', [PortalController::class, 'getDetailSiswa'])->name('portal.detail');

Route::get('/dashboard', function () {
    $totalSiswa = Siswa::count();
    $totalPelanggaran = RiwayatPelanggaran::count();
   $riwayatTerbaru = RiwayatPelanggaran::with(['siswa', 'pelanggaran', 'user'])
                        ->orderBy('id', 'desc')
                        ->get();
    
    return Inertia::render('Dashboard', [
        'stats' => [
            'total_siswa' => $totalSiswa,
            'total_pelanggaran' => $totalPelanggaran,
        ],
        'riwayatTerbaru' => $riwayatTerbaru,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::delete('/pelanggaran/{id}', function ($id) {
    $riwayat = RiwayatPelanggaran::findOrFail($id);
    $siswa = Siswa::find($riwayat->siswa_id);
    $pelanggaran = App\Models\Pelanggaran::find($riwayat->pelanggaran_id);
    $siswa->decrement('total_poin_pelanggaran', $pelanggaran->poin_pelanggaran);
    $riwayat->delete();

})->middleware(['auth'])->name('pelanggaran.destroy');

Route::post('/siswa', function (Request $request) {
    $request->validate([
        'nama' => 'required|string|max:255',
        'nisn' => 'required|string|unique:siswas,nisn',
        'kelas' => 'required|string',
        'rfid_uid' => 'required|string|unique:siswas,rfid_uid',
    ]);

    Siswa::create([
        'nama' => $request->nama,
        'nisn' => $request->nisn,
        'kelas' => $request->kelas,
        'rfid_uid' => $request->rfid_uid,
        'total_poin_pelanggaran' => 0 // Awal mula siswa suci tanpa dosa (0 Poin)
    ]);

    return back();
})->middleware(['auth'])->name('siswa.store');


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