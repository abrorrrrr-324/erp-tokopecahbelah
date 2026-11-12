<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupplierCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplierUser = User::query()->where('email', 'supplier@gmail.com')->firstOrFail();
        $admin = User::query()->where('email', 'admin@gmail.com')->firstOrFail();

        $supplier = Supplier::query()->updateOrCreate(
            ['user_id' => $supplierUser->id],
            [
                'nama' => 'Supplier Perlengkapan Rumah',
                'kontak_person' => 'Tim Penjualan',
                'email' => $supplierUser->email,
                'telepon' => '081234567890',
                'alamat' => 'Jakarta, Indonesia',
                'status' => 'aktif',
            ],
        );

        $categories = collect([
            ['nama' => 'Alat Makan', 'deskripsi' => 'Peralatan makan dan minum'],
            ['nama' => 'Alat Mandi', 'deskripsi' => 'Perlengkapan kamar mandi'],
            ['nama' => 'Kebersihan', 'deskripsi' => 'Peralatan kebersihan rumah'],
            ['nama' => 'Perlengkapan Rumah', 'deskripsi' => 'Kebutuhan rumah tangga'],
        ])->mapWithKeys(function (array $category): array {
            $record = ProductCategory::query()->updateOrCreate(
                ['nama' => $category['nama']],
                ['deskripsi' => $category['deskripsi'], 'status' => 'aktif'],
            );

            return [$category['nama'] => $record->id];
        });

        $products = [
            [
                'sku' => 'SUP-GLS-001',
                'nama' => 'Gelas Kaca Bening',
                'deskripsi' => 'Gelas kaca untuk kebutuhan rumah dan usaha.',
                'category_id' => $categories['Alat Makan'],
                'harga_beli' => 7000,
                'harga_jual' => 12000,
                'satuan' => 'pcs',
                'stok' => 250,
            ],
            [
                'sku' => 'SUP-PRT-002',
                'nama' => 'Piring Melamin',
                'deskripsi' => 'Piring makan berbahan melamin.',
                'category_id' => $categories['Alat Makan'],
                'harga_beli' => 6000,
                'harga_jual' => 10000,
                'satuan' => 'pcs',
                'stok' => 300,
            ],
            [
                'sku' => 'SUP-EMB-003',
                'nama' => 'Ember Plastik',
                'deskripsi' => 'Ember plastik serbaguna.',
                'category_id' => $categories['Perlengkapan Rumah'],
                'harga_beli' => 12000,
                'harga_jual' => 18000,
                'satuan' => 'pcs',
                'stok' => 150,
            ],
            [
                'sku' => 'SUP-SPN-004',
                'nama' => 'Spons Cuci Piring',
                'deskripsi' => 'Spons untuk mencuci peralatan makan.',
                'category_id' => $categories['Kebersihan'],
                'harga_beli' => 2000,
                'harga_jual' => 3500,
                'satuan' => 'pcs',
                'stok' => 500,
            ],
            [
                'sku' => 'SUP-SAP-005',
                'nama' => 'Sapu Lantai',
                'deskripsi' => 'Sapu untuk membersihkan lantai.',
                'category_id' => $categories['Kebersihan'],
                'harga_beli' => 15000,
                'harga_jual' => 20000,
                'satuan' => 'pcs',
                'stok' => 120,
            ],
        ];

        foreach ($products as $product) {
            SupplierProduct::query()->updateOrCreate(
                ['supplier_id' => $supplier->id, 'sku' => $product['sku']],
                [...$product, 'supplier_id' => $supplier->id, 'status' => 'aktif'],
            );
        }

        $sampleProduct = SupplierProduct::query()->where('sku', 'SUP-GLS-001')->firstOrFail();
        $order = PurchaseOrder::query()->firstOrCreate(
            ['supplier_id' => $supplier->id, 'catatan' => 'Pesanan contoh dari seeder'],
            [
                'user_id' => $admin->id,
                'tanggal_pesanan' => now(),
                'total' => (float) $sampleProduct->harga_beli * 12,
                'status' => 'menunggu',
            ],
        );

        $order->items()->firstOrCreate(
            ['produk_id' => $sampleProduct->id],
            [
                'jumlah' => 12,
                'harga' => $sampleProduct->harga_beli,
                'sub_total' => (float) $sampleProduct->harga_beli * 12,
            ],
        );
    }
}
