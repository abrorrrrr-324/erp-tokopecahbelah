<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Toko
        User::updateOrCreate(
            ['email' => 'admin@toko.test'],
            [
                'name' => 'Admin Toko',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Kepala Gudang
        User::updateOrCreate(
            ['email' => 'gudang@toko.test'],
            [
                'name' => 'Kepala Gudang',
                'role' => 'kepala_gudang',
                'password' => Hash::make('gudang123'),
            ]
        );

        // 3. Supplier
        User::updateOrCreate(
            ['email' => 'supplier@toko.test'],
            [
                'name' => 'Supplier',
                'role' => 'supplier',
                'password' => Hash::make('supplier123'),
            ]
        );

        // 4. Customer
        User::updateOrCreate(
            ['email' => 'customer@toko.test'],
            [
                'name' => 'Customer',
                'role' => 'customer',
                'password' => Hash::make('customer123'),
            ]
        );

        $this->call(ProductSeeder::class);
    }
}
