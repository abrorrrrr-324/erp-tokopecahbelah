<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed data katalog produk.
     */
    public function run(): void
    {
        $adminId = User::where('email', 'admin@gmail.com')->value('id');

        if (!$adminId) {
            $this->command->error(
                'Admin dengan email admin@gmail.com tidak ditemukan.'
            );

            return;
        }

        $products = [
            [
                'sku' => 'PRD-001',
                'name' => 'Canteng',
                'description' => 'Perlengkapan alat mandi',
                'category' => 'alat mandi',
                'buy_price' => 3000,
                'price' => 5000,
                'unit' => 'pcs',
                'stock' => 43,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-002',
                'name' => 'Gelas Kaca',
                'description' => 'Gelas kaca untuk minuman',
                'category' => 'alat makan',
                'buy_price' => 7000,
                'price' => 12000,
                'unit' => 'pcs',
                'stock' => 45,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-003',
                'name' => 'Tempat Sampah',
                'description' => 'Tempat sampah untuk rumah dan kantor',
                'category' => 'kebersihan',
                'buy_price' => 16000,
                'price' => 20000,
                'unit' => 'pcs',
                'stock' => 65,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-004',
                'name' => 'Tepak Makan',
                'description' => 'Kotak bekal makanan',
                'category' => 'alat makan',
                'buy_price' => 17000,
                'price' => 25000,
                'unit' => 'pcs',
                'stock' => 78,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-005',
                'name' => 'Sapu-Sapu',
                'description' => 'Sapu untuk membersihkan lantai',
                'category' => 'kebersihan',
                'buy_price' => 15000,
                'price' => 20000,
                'unit' => 'pcs',
                'stock' => 200,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-006',
                'name' => 'Ember Plastik',
                'description' => 'Ember plastik serbaguna',
                'category' => 'perlengkapan rumah',
                'buy_price' => 12000,
                'price' => 18000,
                'unit' => 'pcs',
                'stock' => 35,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-007',
                'name' => 'Gayung Mandi',
                'description' => 'Gayung plastik untuk mandi',
                'category' => 'alat mandi',
                'buy_price' => 4000,
                'price' => 7000,
                'unit' => 'pcs',
                'stock' => 8,
                'minimum_stock' => 10,
                'status' => 'Hampir habis',
            ],
            [
                'sku' => 'PRD-008',
                'name' => 'Sikat Gigi',
                'description' => 'Sikat gigi untuk penggunaan harian',
                'category' => 'alat mandi',
                'buy_price' => 3000,
                'price' => 5000,
                'unit' => 'pcs',
                'stock' => 120,
                'minimum_stock' => 20,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-009',
                'name' => 'Piring Melamin',
                'description' => 'Piring makan berbahan melamin',
                'category' => 'alat makan',
                'buy_price' => 6000,
                'price' => 10000,
                'unit' => 'pcs',
                'stock' => 56,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-010',
                'name' => 'Sendok Stainless',
                'description' => 'Sendok makan berbahan stainless steel',
                'category' => 'alat makan',
                'buy_price' => 2500,
                'price' => 4000,
                'unit' => 'pcs',
                'stock' => 150,
                'minimum_stock' => 20,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-011',
                'name' => 'Tempat Sabun',
                'description' => 'Wadah sabun untuk kamar mandi',
                'category' => 'alat mandi',
                'buy_price' => 5000,
                'price' => 8000,
                'unit' => 'pcs',
                'stock' => 25,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-012',
                'name' => 'Pel Lantai',
                'description' => 'Alat pel untuk membersihkan lantai',
                'category' => 'kebersihan',
                'buy_price' => 18000,
                'price' => 25000,
                'unit' => 'pcs',
                'stock' => 18,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-013',
                'name' => 'Spons Cuci Piring',
                'description' => 'Spons untuk mencuci peralatan makan',
                'category' => 'kebersihan',
                'buy_price' => 2000,
                'price' => 3500,
                'unit' => 'pcs',
                'stock' => 90,
                'minimum_stock' => 15,
                'status' => 'Promo',
            ],
            [
                'sku' => 'PRD-014',
                'name' => 'Botol Minum',
                'description' => 'Botol minum untuk sekolah dan bekerja',
                'category' => 'alat minum',
                'buy_price' => 12000,
                'price' => 18000,
                'unit' => 'pcs',
                'stock' => 42,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-015',
                'name' => 'Teko Plastik',
                'description' => 'Teko plastik untuk menyimpan air minum',
                'category' => 'alat minum',
                'buy_price' => 14000,
                'price' => 22000,
                'unit' => 'pcs',
                'stock' => 30,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-016',
                'name' => 'Gantungan Baju',
                'description' => 'Gantungan pakaian untuk lemari',
                'category' => 'perlengkapan rumah',
                'buy_price' => 1500,
                'price' => 2500,
                'unit' => 'pcs',
                'stock' => 100,
                'minimum_stock' => 20,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-017',
                'name' => 'Baskom Plastik',
                'description' => 'Baskom plastik serbaguna',
                'category' => 'perlengkapan rumah',
                'buy_price' => 10000,
                'price' => 15000,
                'unit' => 'pcs',
                'stock' => 7,
                'minimum_stock' => 10,
                'status' => 'Hampir habis',
            ],
            [
                'sku' => 'PRD-018',
                'name' => 'Tempat Pensil',
                'description' => 'Tempat menyimpan alat tulis',
                'category' => 'alat tulis',
                'buy_price' => 8000,
                'price' => 12000,
                'unit' => 'pcs',
                'stock' => 38,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-019',
                'name' => 'Buku Tulis',
                'description' => 'Buku tulis untuk sekolah dan perkantoran',
                'category' => 'alat tulis',
                'buy_price' => 3500,
                'price' => 5000,
                'unit' => 'pcs',
                'stock' => 85,
                'minimum_stock' => 15,
                'status' => 'Promo',
            ],
            [
                'sku' => 'PRD-020',
                'name' => 'Lap Microfiber',
                'description' => 'Kain lap untuk membersihkan berbagai permukaan',
                'category' => 'kebersihan',
                'buy_price' => 5000,
                'price' => 8000,
                'unit' => 'pcs',
                'stock' => 60,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'category' => $product['category'],
                    'buy_price' => $product['buy_price'],
                    'price' => $product['price'],
                    'unit' => $product['unit'],
                    'stock' => $product['stock'],
                    'minimum_stock' => $product['minimum_stock'],
                    'status' => $product['status'],
                    'photo' => null,
                    'created_by' => $adminId,
                ]
            );
        }

        $this->command->info(
            'Seeder berhasil: 20 produk katalog siap digunakan.'
        );
    }
}
