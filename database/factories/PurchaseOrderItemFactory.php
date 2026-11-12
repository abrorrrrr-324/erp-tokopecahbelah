<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SupplierProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseOrderItem>
 */
class PurchaseOrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'produk_id' => SupplierProduct::factory(),
            'jumlah' => fake()->numberBetween(1, 50),
            'harga' => fake()->numberBetween(1000, 100000),
            'sub_total' => fake()->numberBetween(1000, 5000000),
        ];
    }
}
