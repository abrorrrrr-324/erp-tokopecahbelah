<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'supplier']),
            'nama' => fake()->company(),
            'kontak_person' => fake()->name(),
            'email' => fake()->unique()->companyEmail(),
            'telepon' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'status' => 'aktif',
        ];
    }
}
