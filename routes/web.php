<?php

use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route('login');
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
        $productTable = Schema::hasTable('products') ? 'products' : (Schema::hasTable('produk') ? 'produk' : null);
        $totalProduk = $productTable ? Product::query()->from($productTable)->count() : 0;
        $stockColumn = $productTable === 'produk' ? 'stok' : 'stock';
        $totalStok = $productTable ? (int) Product::query()->from($productTable)->sum($stockColumn) : 0;
        $customerCount = User::query()->where('role', 'customer')->count();
        $supplierCount = User::query()->where('role', 'supplier')->count();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalStok',
            'customerCount',
            'supplierCount',
        ));
    })->name('admin.dashboard');

    Route::get('/admin/katalog', [AdminCatalogController::class, 'index'])->name('admin.katalog');
    Route::get('/admin/katalog/create', [AdminCatalogController::class, 'create'])->name('admin.katalog.create');
    Route::post('/admin/katalog', [AdminCatalogController::class, 'store'])->name('admin.katalog.store');
    Route::get('/admin/katalog/{product}', [AdminCatalogController::class, 'show'])->name('admin.katalog.show');
    Route::get('/admin/katalog/{product}/edit', [AdminCatalogController::class, 'edit'])->name('admin.katalog.edit');
    Route::put('/admin/katalog/{product}', [AdminCatalogController::class, 'update'])->name('admin.katalog.update');
    Route::delete('/admin/katalog/{product}', [AdminCatalogController::class, 'destroy'])->name('admin.katalog.destroy');

    Route::get('/admin/customer', [AdminUserController::class, 'customerIndex'])->name('admin.customer');
    Route::get('/admin/customer/create', [AdminUserController::class, 'customerCreate'])->name('admin.customer.create');
    Route::get('/admin/customer/{user}', [AdminUserController::class, 'customerShow'])->name('admin.customer.show');
    Route::get('/admin/customer/{user}/edit', [AdminUserController::class, 'customerEdit'])->name('admin.customer.edit');
    Route::post('/admin/customer', [AdminUserController::class, 'storeCustomer'])->name('admin.customer.store');
    Route::put('/admin/customer/{user}', [AdminUserController::class, 'updateCustomer'])->name('admin.customer.update');
    Route::delete('/admin/customer/{user}', [AdminUserController::class, 'destroyCustomer'])->name('admin.customer.destroy');

    Route::get('/admin/supplier', [AdminUserController::class, 'supplierIndex'])->name('admin.supplier');
    Route::get('/admin/supplier/create', [AdminUserController::class, 'supplierCreate'])->name('admin.supplier.create');
    Route::get('/admin/supplier/{user}', [AdminUserController::class, 'supplierShow'])->name('admin.supplier.show');
    Route::get('/admin/supplier/{user}/edit', [AdminUserController::class, 'supplierEdit'])->name('admin.supplier.edit');
    Route::post('/admin/supplier', [AdminUserController::class, 'storeSupplier'])->name('admin.supplier.store');
    Route::put('/admin/supplier/{user}', [AdminUserController::class, 'updateSupplier'])->name('admin.supplier.update');
    Route::delete('/admin/supplier/{user}', [AdminUserController::class, 'destroySupplier'])->name('admin.supplier.destroy');

    Route::get('/admin/transaksi', function () {
        return view('admin.transaksi.index');
    })->name('admin.transaksi');

    Route::get('/admin/pembelian', function () {
        return view('admin.pembelian.index');
    })->name('admin.pembelian');

    Route::get('/admin/stok', function () {
        return view('admin.stok.index');
    })->name('admin.stok');

    Route::get('/admin/laporan', function () {
        return view('admin.laporan.index');
    })->name('admin.laporan');

});

// ===============================
// KEPALA GUDANG
// ===============================

Route::middleware(['auth', 'role:kepala_gudang'])->group(function () {

    Route::get('/gudang/dashboard', function () {
        return view('kepalagudang.dashboard');
    })->name('gudang.dashboard');

    Route::get('/gudang/katalog', [AdminCatalogController::class, 'gudangIndex'])->name('gudang.katalog');
    Route::get('/gudang/katalog/{product}', [AdminCatalogController::class, 'gudangShow'])->name('gudang.katalog.show');

});

// ===============================
// SUPPLIER
// ===============================

Route::middleware(['auth', 'role:supplier'])->group(function () {

    Route::get('/supplier/dashboard', function () {
        return view('supplier.dashboard');
    })->name('supplier.dashboard');

});

// ===============================
// CUSTOMER
// ===============================

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    Route::get('/customer/katalog', [AdminCatalogController::class, 'customerIndex'])->name('customer.katalog');
    Route::get('/customer/katalog/{product}', [AdminCatalogController::class, 'customerShow'])->name('customer.katalog.show');

    Route::get('/costumer/dashboard', function () {
        return view('customer.dashboard');
    })->name('costumer.dashboard');

});
