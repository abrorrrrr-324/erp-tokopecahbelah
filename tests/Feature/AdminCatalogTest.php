<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_catalog_page_loads_products_from_database_and_shows_logged_in_user(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.test',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        Product::create([
            'name' => 'Kursi Kayu Minimalis',
            'category' => 'Furniture',
            'stock' => 32,
            'price' => 1250000,
            'status' => 'Aktif',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.katalog'))
            ->assertOk()
            ->assertSee('Kursi Kayu Minimalis')
            ->assertSee('Admin Toko');
    }
}
