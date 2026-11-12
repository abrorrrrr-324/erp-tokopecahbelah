<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\SupplierProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupplierProduct>
 */
class SupplierProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'sku' => fake()->unique()->bothify('SUP-#####'),
            'nama' => fake()->words(3, true),
            'deskripsi' => fake()->sentence(),
            'harga_beli' => fake()->numberBetween(1000, 100000),
            'harga_jual' => 0,
            'satuan' => 'pcs',
            'stok' => fake()->numberBetween(0, 500),
            'status' => 'aktif',
            'category_id' => null,
        ];
    }
}
