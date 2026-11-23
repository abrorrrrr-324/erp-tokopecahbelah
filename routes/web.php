
<?php

use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenerimaanBarangController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StockAuditController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierCatalogController;
use App\Http\Controllers\WarehouseDashboardController;
use App\Http\Controllers\WarehouseReportController;
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
        $productTable = Schema::hasTable('products')
            ? 'products'
            : (Schema::hasTable('produk') ? 'produk' : null);

        $totalProduk = $productTable
            ? Product::query()->from($productTable)->count()
            : 0;

        $stockColumn = $productTable === 'produk' ? 'stok' : 'stock';

        $totalStok = $productTable
            ? (int) Product::query()->from($productTable)->sum($stockColumn)
            : 0;

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

    Route::get('/admin/pembelian', [PurchaseOrderController::class, 'index'])->name('admin.pembelian');
    Route::get('/admin/pembelian/create', [PurchaseOrderController::class, 'create'])->name('admin.pembelian.create');
    Route::post('/admin/pembelian', [PurchaseOrderController::class, 'store'])->name('admin.pembelian.store');
    Route::get('/admin/pembelian/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('admin.pembelian.show');
    Route::post('/admin/pembelian/{purchaseOrder}/checkout', [PurchaseOrderController::class, 'checkout'])->name('admin.pembelian.checkout');
    Route::post('/admin/pembelian/{purchaseOrder}/bayar-qris', [PurchaseOrderController::class, 'payWithQris'])->name('admin.pembelian.qris.pay');

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

    Route::get('/gudang/dashboard', [WarehouseDashboardController::class, 'index'])
        ->name('gudang.dashboard');

    Route::get('/gudang/katalog', [AdminCatalogController::class, 'gudangIndex'])
        ->name('gudang.katalog');

    Route::get('/gudang/katalog/{product}', [AdminCatalogController::class, 'gudangShow'])
        ->name('gudang.katalog.show');

    // CEK STOK
    Route::get('/gudang/cek-stok', [AdminCatalogController::class, 'cekStok'])
        ->name('gudang.cek-stok');

    Route::get('/gudang/stok/mutasi', [StockMovementController::class, 'index'])
        ->name('gudang.stok.mutasi');
    Route::get('/gudang/stok/mutasi/create', [StockMovementController::class, 'create'])
        ->name('gudang.stok.mutasi.create');
    Route::post('/gudang/stok/mutasi', [StockMovementController::class, 'store'])
        ->name('gudang.stok.mutasi.store');

    Route::get('/gudang/audit', [StockAuditController::class, 'index'])
        ->name('gudang.audit');
    Route::get('/gudang/audit/create', [StockAuditController::class, 'create'])
        ->name('gudang.audit.create');
    Route::post('/gudang/audit', [StockAuditController::class, 'store'])
        ->name('gudang.audit.store');
    Route::get('/gudang/audit/{stockAudit}', [StockAuditController::class, 'show'])
        ->name('gudang.audit.show');
    Route::get('/gudang/audit/{stockAudit}/foto', [StockAuditController::class, 'foto'])
        ->name('gudang.audit.foto');
    Route::post('/gudang/audit/{stockAudit}/verifikasi', [StockAuditController::class, 'review'])
        ->name('gudang.audit.review');

    Route::get('/gudang/laporan', [WarehouseReportController::class, 'index'])
        ->name('gudang.laporan');
    Route::get('/gudang/laporan/cetak', [WarehouseReportController::class, 'print'])
        ->name('gudang.laporan.print');
    Route::get('/gudang/laporan/pdf', [WarehouseReportController::class, 'pdf'])
        ->name('gudang.laporan.pdf');
    Route::get('/gudang/laporan/excel', [WarehouseReportController::class, 'excel'])
        ->name('gudang.laporan.excel');

    // PENERIMAAN BARANG
    Route::get('/gudang/penerimaan', [PenerimaanBarangController::class, 'index'])
        ->name('gudang.penerimaan');

    Route::get('/gudang/penerimaan/{purchaseOrder}', [PenerimaanBarangController::class, 'show'])
        ->name('gudang.penerimaan.show');
    Route::get('/gudang/penerimaan/{penerimaanBarang}/foto', [PenerimaanBarangController::class, 'foto'])
        ->name('gudang.penerimaan.foto');

    Route::post('/gudang/penerimaan/{purchaseOrder}/verifikasi', [PenerimaanBarangController::class, 'verifikasi'])
        ->name('gudang.penerimaan.verifikasi');
    Route::post('/gudang/penerimaan/{penerimaanBarang}/tindak-lanjut', [PenerimaanBarangController::class, 'tindakLanjut'])
        ->name('gudang.penerimaan.follow-up');
});

// ===============================
// SUPPLIER
// ===============================

Route::middleware(['auth', 'role:supplier'])->group(function () {

    Route::get('/supplier/dashboard', [PurchaseOrderController::class, 'supplierDashboard'])->name('supplier.dashboard');
    Route::get('/supplier/pesanan', [PurchaseOrderController::class, 'supplierIndex'])->name('supplier.pesanan');
    Route::get('/supplier/pesanan/{purchaseOrder}', [PurchaseOrderController::class, 'supplierShow'])->name('supplier.pesanan.show');
    Route::post('/supplier/pesanan/{purchaseOrder}/konfirmasi', [PurchaseOrderController::class, 'supplierConfirm'])->name('supplier.pesanan.confirm');
    Route::post('/supplier/pesanan/{purchaseOrder}/tolak', [PurchaseOrderController::class, 'supplierReject'])->name('supplier.pesanan.reject');

    // KIRIM BARANG
    Route::post('/supplier/pesanan/{purchaseOrder}/kirim', [PurchaseOrderController::class, 'supplierShip'])
        ->name('supplier.pesanan.ship');

    Route::get('/supplier/katalog', [SupplierCatalogController::class, 'index'])->name('supplier.katalog');
    Route::get('/supplier/katalog/create', [SupplierCatalogController::class, 'create'])->name('supplier.katalog.create');
    Route::post('/supplier/katalog', [SupplierCatalogController::class, 'store'])->name('supplier.katalog.store');
    Route::get('/supplier/katalog/{supplierProduct}/edit', [SupplierCatalogController::class, 'edit'])->name('supplier.katalog.edit');
    Route::put('/supplier/katalog/{supplierProduct}', [SupplierCatalogController::class, 'update'])->name('supplier.katalog.update');
    Route::delete('/supplier/katalog/{supplierProduct}', [SupplierCatalogController::class, 'destroy'])->name('supplier.katalog.destroy');
});

// ===============================
// CUSTOMER
// ===============================

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');

    Route::get('/customer/katalog', [AdminCatalogController::class, 'customerIndex'])
        ->name('customer.katalog');

    Route::get('/customer/katalog/{product}', [AdminCatalogController::class, 'customerShow'])
        ->name('customer.katalog.show');

    Route::get('/costumer/dashboard', function () {
        return view('customer.dashboard');
    })->name('costumer.dashboard');
});
