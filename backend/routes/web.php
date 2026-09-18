<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WEB\AdminController;
use App\Http\Controllers\WEB\PetugasController;
use App\Http\Controllers\WEB\PeminjamController;
use App\Http\Controllers\WEB\AuthController;

Route::get('/', function () {
    return view('welcome');
});


// ==================== ADMIN ====================

Route::middleware(['auth', 'role.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');


        // ==================== CRUD ALAT ====================

        Route::get('/alat', [AdminController::class, 'indexAlat'])
            ->name('alat.index');

        Route::get('/alat/create', [AdminController::class, 'createAlat'])
            ->name('alat.create');

        Route::post('/alat', [AdminController::class, 'storeAlat'])
            ->name('alat.store');

        Route::get('/alat/{id}/edit', [AdminController::class, 'editAlat'])
            ->name('alat.edit');

        Route::put('/alat/{id}', [AdminController::class, 'updateAlat'])
            ->name('alat.update');

        Route::delete('/alat/{id}', [AdminController::class, 'destroyAlat'])
            ->name('alat.destroy');

        Route::get('/profile', [AdminController::class, 'profile'])
            ->name('profile');
            
        Route::get('/profile/edit', [AdminController::class, 'editProfile'])
            ->name('profile.edit');

        Route::put('/profile', [AdminController::class, 'updateProfile'])
            ->name('profile.update');


        // ==================== CRUD USER ====================

        Route::get('/users', [AdminController::class, 'indexUser'])
            ->name('user.index');

        Route::get('/users/create', [AdminController::class, 'createUser'])
            ->name('user.create');

        Route::post('/users', [AdminController::class, 'storeUser'])
            ->name('user.store');

        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])
            ->name('user.edit');

        Route::put('/users/{id}', [AdminController::class, 'updateUser'])
            ->name('user.update');

        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])
            ->name('user.destroy');


        // ==================== CRUD KATEGORI ====================

        Route::get('/kategori', [AdminController::class, 'indexKategori'])
            ->name('kategori.index');

        Route::get('/kategori/create', [AdminController::class, 'createKategori'])
            ->name('kategori.create');

        Route::post('/kategori', [AdminController::class, 'storeKategori'])
            ->name('kategori.store');

        Route::get('/kategori/{id}/edit', [AdminController::class, 'editKategori'])
            ->name('kategori.edit');

        Route::put('/kategori/{id}', [AdminController::class, 'updateKategori'])
            ->name('kategori.update');

        Route::delete('/kategori/{id}', [AdminController::class, 'destroyKategori'])
            ->name('kategori.destroy');


        // ==================== CRUD PEMINJAMAN ====================

        Route::get('/peminjaman', [AdminController::class, 'indexPeminjaman'])
            ->name('peminjaman.index');

        Route::get('/peminjaman/create', [AdminController::class, 'createPeminjaman'])
            ->name('peminjaman.create');

        Route::post('/peminjaman', [AdminController::class, 'storePeminjaman'])
            ->name('peminjaman.store');

        Route::get('/peminjaman/{id}/edit', [AdminController::class, 'editPeminjaman'])
            ->name('peminjaman.edit');

        Route::put('/peminjaman/{id}', [AdminController::class, 'updatePeminjaman'])
            ->name('peminjaman.update');

        Route::delete('/peminjaman/{id}', [AdminController::class, 'destroyPeminjaman'])
            ->name('peminjaman.destroy');


        // ==================== UPDATE STATUS PEMINJAMAN ====================

        Route::put('/peminjaman/{id}/status', [AdminController::class, 'updateStatusPeminjaman'])
            ->name('peminjaman.updateStatus');


        // ==================== PENGEMBALIAN ====================

        // CRUD Pengembalian

        Route::get('/pengembalian', [AdminController::class, 'indexPengembalian'])
            ->name('pengembalian.index');

        Route::get('/pengembalian/create', [AdminController::class, 'createPengembalian'])
            ->name('pengembalian.create');

        Route::post('/pengembalian', [AdminController::class, 'storePengembalian'])
            ->name('pengembalian.store');

        // EDIT PENGEMBALIAN
        Route::get('/pengembalian/{id}/edit', [AdminController::class, 'editPengembalian'])
            ->name('pengembalian.edit');

        // UPDATE PENGEMBALIAN
        Route::put('/pengembalian/{id}', [AdminController::class, 'updatePengembalian'])
            ->name('pengembalian.update');

        // HAPUS PENGEMBALIAN
        Route::delete('/pengembalian/{id}', [AdminController::class, 'destroyPengembalian'])
            ->name('pengembalian.destroy');
    });


// ==================== PETUGAS ====================

Route::middleware(['auth', 'role.petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        // ==================== PROFIL ====================
        Route::get('/profile', [PetugasController::class, 'profile'])
            ->name('profile');

        Route::get('/profile/edit', [PetugasController::class, 'editProfile'])
            ->name('profile.edit');

        Route::put('/profile', [PetugasController::class, 'updateProfile'])
            ->name('profile.update');


        // ==================== PEMINJAMAN & PERSETUJUAN ====================
        Route::get('/peminjaman', [PetugasController::class, 'indexPeminjaman'])
            ->name('peminjaman.index');

        Route::post('/peminjaman/{id}/setujui', [PetugasController::class, 'setujuiPeminjaman'])
            ->name('peminjaman.setujui');

        Route::post('/peminjaman/{id}/tolak', [PetugasController::class, 'tolakPeminjaman'])
            ->name('peminjaman.tolak');


        // ==================== PENGEMBALIAN & DENDA ====================
        Route::get('/pengembalian', [PetugasController::class, 'indexPengembalian'])
            ->name('pengembalian.index');

        Route::get('/pengembalian/{id}', [PetugasController::class, 'showPengembalian'])
            ->name('pengembalian.show');

        Route::post('/pengembalian/{id}', [PetugasController::class, 'prosesPengembalian'])
            ->name('pengembalian.proses');


        // ==================== LAPORAN ====================
        Route::get('/laporan', [PetugasController::class, 'laporan'])
            ->name('laporan.index');

        Route::get('/laporan/cetak', [PetugasController::class, 'cetakLaporan'])
            ->name('laporan.cetak');
    });


// ==================== PEMINJAM ====================

Route::middleware(['auth', 'role.peminjam'])
    ->prefix('peminjam')
    ->name('peminjam.')
    ->group(function () {

        // ==================== PROFIL ====================
        Route::get('/profile', [PeminjamController::class, 'profile'])
            ->name('profile');

        Route::get('/profile/edit', [PeminjamController::class, 'editProfile'])
            ->name('profile.edit');

        Route::put('/profile', [PeminjamController::class, 'updateProfile'])
            ->name('profile.update');


        // ==================== KATALOG & PENGAJUAN ====================
        Route::get('/katalog', [PeminjamController::class, 'katalogAlat'])
            ->name('alat.index');


        // ==================== PEMINJAMAN ====================
        Route::get('/peminjaman', [PeminjamController::class, 'indexPeminjaman'])
            ->name('peminjaman.index');


        // ==================== PENGEMBALIAN ====================
        Route::get('/pengembalian', [PeminjamController::class, 'indexPengembalian'])
            ->name('pengembalian.index');


        // ==================== AJUKAN PEMINJAMAN ====================
        Route::post('/peminjaman/ajukan', [PeminjamController::class, 'ajukanPeminjaman'])
            ->name('peminjaman.ajukan');


        // ==================== RIWAYAT ====================
        Route::get('/riwayat', [PeminjamController::class, 'riwayatPeminjaman'])
            ->name('riwayat');
    });


// ==================== ROUTE TAMU ====================

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});


// ==================== LOGOUT ====================

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');