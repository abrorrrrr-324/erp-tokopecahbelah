<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@toko.test'],
            [
                'name' => 'Admin Toko',
                'role' => 'admin',
                'password' => 'admin123',
            ]
        );

        Product::query()->delete();

        Product::query()->insert([
            [
                'name' => 'Kursi Kayu Minimalis',
                'category' => 'Furniture',
                'stock' => 32,
                'price' => 1250000,
                'status' => 'Aktif',
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lemari 3 Pintu',
                'category' => 'Furniture',
                'stock' => 14,
                'price' => 2400000,
                'status' => 'Aktif',
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lampu LED 12W',
                'category' => 'Elektronik',
                'stock' => 67,
                'price' => 220000,
                'status' => 'Promo',
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sprei Queen Set',
                'category' => 'Textil',
                'stock' => 9,
                'price' => 480000,
                'status' => 'Hampir habis',
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
