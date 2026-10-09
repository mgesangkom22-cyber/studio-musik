<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\Admin\AlatMusikController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\PendaftaranAnggotaController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\CatatanPelanggaranController;
use App\Http\Controllers\Admin\LaporanController;

/*
|--------------------------------------------------------------------------
| Public Routes (Bebas Akses Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index']);
Route::get('/daftar', [PendaftaranController::class, 'create']);
Route::post('/daftar', [PendaftaranController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Public RFID API Endpoints (For ESP32 & Web Polling Fallbacks)
|--------------------------------------------------------------------------
*/
Route::post('/rfid/register', [\App\Http\Controllers\Api\RfidApiController::class, 'registerScan']);
Route::post('/rfid/scan', [\App\Http\Controllers\Api\RfidApiController::class, 'memberScan']);
Route::get('/rfid/latest-scanned', [\App\Http\Controllers\Api\RfidApiController::class, 'getLatestScanned']);
Route::post('/rfid/clear-latest', [\App\Http\Controllers\Api\RfidApiController::class, 'clearLatestScanned']);

Route::post('/api/rfid/register', [\App\Http\Controllers\Api\RfidApiController::class, 'registerScan']);
Route::post('/api/rfid/scan', [\App\Http\Controllers\Api\RfidApiController::class, 'memberScan']);
Route::get('/api/rfid/latest-scanned', [\App\Http\Controllers\Api\RfidApiController::class, 'getLatestScanned']);
Route::post('/api/rfid/clear-latest', [\App\Http\Controllers\Api\RfidApiController::class, 'clearLatestScanned']);

Route::post('/studio-musik/api/rfid/register', [\App\Http\Controllers\Api\RfidApiController::class, 'registerScan']);
Route::post('/studio-musik/api/rfid/scan', [\App\Http\Controllers\Api\RfidApiController::class, 'memberScan']);
Route::get('/studio-musik/api/rfid/latest-scanned', [\App\Http\Controllers\Api\RfidApiController::class, 'getLatestScanned']);
Route::post('/studio-musik/api/rfid/clear-latest', [\App\Http\Controllers\Api\RfidApiController::class, 'clearLatestScanned']);

Route::post('/studio-musik/rfid/register', [\App\Http\Controllers\Api\RfidApiController::class, 'registerScan']);
Route::post('/studio-musik/rfid/scan', [\App\Http\Controllers\Api\RfidApiController::class, 'memberScan']);
Route::get('/studio-musik/rfid/latest-scanned', [\App\Http\Controllers\Api\RfidApiController::class, 'getLatestScanned']);
Route::post('/studio-musik/rfid/clear-latest', [\App\Http\Controllers\Api\RfidApiController::class, 'clearLatestScanned']);

// Multi-Client Real-Time Display Synchronization Routes (PC & HP Sync)
Route::get('/api/pengembalian/sync-state', [\App\Http\Controllers\Api\RfidApiController::class, 'getPengembalianState']);
Route::post('/api/pengembalian/sync-state', [\App\Http\Controllers\Api\RfidApiController::class, 'updatePengembalianState']);
Route::get('/pengembalian/sync-state', [\App\Http\Controllers\Api\RfidApiController::class, 'getPengembalianState']);
Route::post('/pengembalian/sync-state', [\App\Http\Controllers\Api\RfidApiController::class, 'updatePengembalianState']);

Route::get('/api/peminjaman/sync-state', [\App\Http\Controllers\Api\RfidApiController::class, 'getPeminjamanState']);
Route::post('/api/peminjaman/sync-state', [\App\Http\Controllers\Api\RfidApiController::class, 'updatePeminjamanState']);
Route::get('/peminjaman/sync-state', [\App\Http\Controllers\Api\RfidApiController::class, 'getPeminjamanState']);
Route::post('/peminjaman/sync-state', [\App\Http\Controllers\Api\RfidApiController::class, 'updatePeminjamanState']);



Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Administrator Routes (Wajib Login & Prevent Back History)
|--------------------------------------------------------------------------
*/
Route::middleware(['admin.auth', 'prevent.back'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Pendaftaran Anggota (Admin Verification)
    Route::get('/pendaftaran-anggota', [PendaftaranAnggotaController::class, 'index'])->name('admin.pendaftaran.index');
    Route::get('/pendaftaran-anggota/{id}', [PendaftaranAnggotaController::class, 'show'])->name('admin.pendaftaran.show');
    Route::post('/admin/pendaftaran/{id}/terima', [PendaftaranAnggotaController::class, 'terima'])->name('admin.pendaftaran.terima');
    Route::post('/admin/pendaftaran/{id}/tolak', [PendaftaranAnggotaController::class, 'tolak'])->name('admin.pendaftaran.tolak');

    // Anggota CRUD & RFID Registration
    Route::get('/anggota', [AnggotaController::class, 'index'])->name('admin.anggota.index');
    Route::get('/anggota/check-rfid-availability', [AnggotaController::class, 'checkRfidAvailability'])->name('admin.anggota.check_rfid');
    Route::get('/anggota/find-by-rfid/{uid}', [AnggotaController::class, 'findByRfid'])->name('admin.anggota.by_rfid');
    Route::post('/anggota/{id}/update-rfid', [AnggotaController::class, 'updateRfid'])->name('admin.anggota.update_rfid');
    Route::get('/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
    Route::get('/anggota/{id}', [AnggotaController::class, 'show'])->name('admin.anggota.show');
    Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');

    // Alat Musik CRUD & Barcode
    Route::get('/alat', [AlatMusikController::class, 'index'])->name('admin.alat.index');
    Route::get('/alat/find-by-barcode/{code}', [AlatMusikController::class, 'findByBarcode'])->name('admin.alat.by_barcode');
    Route::get('/alat/create', [AlatMusikController::class, 'create'])->name('admin.alat.create');
    Route::post('/alat', [AlatMusikController::class, 'store'])->name('admin.alat.store');
    Route::get('/alat/{id}/edit', [AlatMusikController::class, 'edit'])->name('admin.alat.edit');
    Route::get('/alat/{id}/cetak-barcode', [AlatMusikController::class, 'cetakBarcode'])->name('admin.alat.cetak_barcode');
    Route::put('/alat/{id}', [AlatMusikController::class, 'update'])->name('admin.alat.update');
    Route::get('/alat/{id}', [AlatMusikController::class, 'show'])->name('admin.alat.show');
    Route::delete('/alat/{id}', [AlatMusikController::class, 'destroy'])->name('admin.alat.destroy');

    // Peminjaman
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('admin.peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('admin.peminjaman.create');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('admin.peminjaman.show');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('admin.peminjaman.store');

    // Pengembalian
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('admin.pengembalian.index');
    Route::get('/pengembalian/find-by-barcode/{code}', [PengembalianController::class, 'findByBarcode'])->name('admin.pengembalian.by_barcode');
    Route::get('/pengembalian/create', [PengembalianController::class, 'create'])->name('admin.pengembalian.create');
    Route::get('/pengembalian/{id}', [PengembalianController::class, 'show'])->name('admin.pengembalian.show');
    Route::post('/pengembalian', [PengembalianController::class, 'store'])->name('admin.pengembalian.store');

    // Pelanggaran & Denda
    Route::get('/catatan-pelanggaran', [CatatanPelanggaranController::class, 'index'])->name('admin.catatan-pelanggaran.index');
    Route::get('/denda', [CatatanPelanggaranController::class, 'dendaIndex'])->name('admin.denda.index');
    Route::post('/denda/{id}/bayar', [CatatanPelanggaranController::class, 'bayarDenda'])->name('admin.denda.bayar');

    // Laporan & Export PDF
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('admin.laporan.pdf');

});