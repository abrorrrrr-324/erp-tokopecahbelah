<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            ->assertSee('Admin Toko')
            ->assertSee('Master Katalog');
    }

    public function test_admin_can_create_product_with_photo(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Storage::fake('public');

        $file = UploadedFile::fake()->image('product.jpg');

        $this->actingAs($admin)
            ->post(route('admin.katalog.store'), [
                'name' => 'Lampu Led',
                'category' => 'Elektronik',
                'stock' => 15,
                'price' => 200000,
                'status' => 'Promo',
                'photo' => $file,
            ])
            ->assertRedirect(route('admin.katalog'))
            ->assertSessionHas('success', 'Produk berhasil ditambahkan.');

        $this->assertDatabaseHas('products', [
            'name' => 'Lampu Led',
            'category' => 'Elektronik',
            'status' => 'Promo',
        ]);

        $product = Product::query()->where('name', 'Lampu Led')->firstOrFail();
        $this->assertNotNull($product->photo);
        Storage::disk('public')->assertExists($product->photo);
    }
}
