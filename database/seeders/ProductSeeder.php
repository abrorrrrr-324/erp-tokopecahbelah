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

        $products = [
            [
                'name' => 'canteng',
                'category' => 'alat mandi',
                'stock' => 43,
                'price' => 5000,
                'status' => 'Aktif',
            ],
            [
                'name' => 'tepak makan',
                'category' => 'alat makan',
                'stock' => 32,
                'price' => 13000,
                'status' => 'Aktif',
            ],
            [
                'name' => 'tempat sampah',
                'category' => 'kebersihan',
                'stock' => 30,
                'price' => 15000,
                'status' => 'Aktif',
            ],
            [
                'name' => 'gelas kaca',
                'category' => 'alat makan',
                'stock' => 10,
                'price' => 2000,
                'status' => 'Aktif',
            ],
            [
                'name' => 'sapusapu',
                'category' => 'kebersihan',
                'stock' => 200,
                'price' => 20000,
                'status' => 'Aktif',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'category' => $product['category'],
                    'stock' => $product['stock'],
                    'price' => $product['price'],
                    'status' => $product['status'],
                    'photo' => null,
                    'created_by' => $adminId,
                ]
            );
        }

        $this->command->info('Seeder berhasil: 5 produk katalog siap digunakan.');
    }
}
