<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category' => fake()->randomElement(['Furniture', 'Elektronik', 'Textil', 'Peralatan']),
            'stock' => fake()->numberBetween(5, 100),
            'price' => fake()->numberBetween(100000, 5000000),
            'status' => fake()->randomElement(['Aktif', 'Promo', 'Hampir habis']),
            'created_by' => User::query()->inRandomOrder()->value('id') ?? User::factory()->create()->id,
        ];
    }
}
