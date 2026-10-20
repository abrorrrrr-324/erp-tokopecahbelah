
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
                'description' => null,
                'category' => 'alat makan',
                'buy_price' => 7000,
                'price' => 12000,
                'unit' => 'dus/6pcs',
                'stock' => 45,
                'minimum_stock' => 10,
                'status' => 'Aktif',
            ],
            [
                'sku' => 'PRD-003',
                'name' => 'Tempat Sampah',
                'description' => null,
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
                'description' => null,
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
                'description' => null,
                'category' => 'kebersihan',
                'buy_price' => 15000,
                'price' => 20000,
                'unit' => 'pcs',
                'stock' => 200,
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
            'Seeder berhasil: 5 produk katalog siap digunakan.'
        );
    }
}
