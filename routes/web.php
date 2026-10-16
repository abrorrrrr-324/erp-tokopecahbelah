<?php

use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

// ===============================
// LOGIN
// ===============================

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// ===============================
// ADMIN TOKO
// ===============================

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');

    Route::get('/admin/katalog', AdminCatalogController::class)->name('admin.katalog');

    Route::get('/admin/customer', function () {
        return view('admin.customer');
    })->name('admin.customer');

    Route::get('/admin/supplier', function () {
        return view('admin.supplier');
    })->name('admin.supplier');

    Route::get('/admin/transaksi', function () {
        return view('admin.transaksi');
    })->name('admin.transaksi');

    Route::get('/admin/pembelian', function () {
        return view('admin.pembelian');
    })->name('admin.pembelian');

    Route::get('/admin/stok', function () {
        return view('admin.stok');
    })->name('admin.stok');

    Route::get('/admin/laporan', function () {
        return view('admin.laporan');
    })->name('admin.laporan');

});

// ===============================
// KEPALA GUDANG
// ===============================

Route::middleware(['auth', 'role:kepala_gudang'])->group(function () {

    Route::get('/gudang/dashboard', function () {
        return view('dashboard.gudang');
    })->name('gudang.dashboard');

});

// ===============================
// SUPPLIER
// ===============================

Route::middleware(['auth', 'role:supplier'])->group(function () {

    Route::get('/supplier/dashboard', function () {
        return view('dashboard.supplier');
    })->name('supplier.dashboard');

});

// ===============================
// CUSTOMER
// ===============================

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/customer/dashboard', function () {
        return view('dashboard.customer');
    })->name('customer.dashboard');

    Route::get('/costumer/dashboard', function () {
        return view('dashboard.customer');
    })->name('costumer.dashboard');

});
