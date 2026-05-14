<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\WarungSetupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/setup-warung', [WarungSetupController::class, 'create'])
        ->name('warung.setup');
    Route::post('/setup-warung', [WarungSetupController::class . 'store'])
        ->name('warung.setup.store');
});

Route::middleware(['auth', 'warung.setup', 'owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('karyawan', KaryawanController::class)
        ->only(['index', 'create', 'store', 'destroy']);

    Route::get('/pengaturan', [PengaturanController::class, 'index'])
        ->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])
        ->name('pengaturan.update');

    Route::resource('produk', ProdukController::class);
    Route::post('produk/{produk}/stok', [ProdukController::class, 'tambahStok'])
        ->name('produk.stok.tambah');
    Route::resource('kategori', CategoryController::class)
        ->only(['index', 'store', 'destroy']);

    // Route::resource('stok', StokController::class);
    // Route::get('/laporan', [LaporanController::class, 'index']);
});

Route::middleware(['auth', 'warung.setup', 'kasir'])->group(function () {
    Route::get('/pos', function () {
        return view('pos.index');
    })->name('pos.index');

    Route::get('/transaksi/riwayat', function () {
        return view('transaksi.riwayat');
    })->name('transaksi.riwayat');
});

require __DIR__ . '/auth.php';