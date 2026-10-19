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

    public function test_gudang_and_customer_can_view_catalog_pages(): void
    {
        Product::create([
            'name' => 'Lemari Minimalis',
            'category' => 'Furniture',
            'stock' => 12,
            'price' => 2450000,
            'status' => 'Aktif',
            'created_by' => User::factory()->create(['role' => 'admin'])->id,
        ]);

        $gudang = User::factory()->create([
            'name' => 'Kepala Gudang',
            'email' => 'gudang@toko.test',
            'role' => 'kepala_gudang',
        ]);

        $customer = User::factory()->create([
            'name' => 'Customer',
            'email' => 'customer@toko.test',
            'role' => 'customer',
        ]);

        $this->actingAs($gudang)
            ->get(route('gudang.katalog'))
            ->assertOk()
            ->assertSee('Lemari Minimalis')
            ->assertSee('Katalog Barang');

        $this->actingAs($customer)
            ->get(route('customer.katalog'))
            ->assertOk()
            ->assertSee('Lemari Minimalis')
            ->assertSee('Katalog Barang');
    }

    public function test_admin_can_manage_customer_and_supplier_records(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.test',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.customer.store'), [
                'name' => 'Budi Customer',
                'email' => 'budi@customer.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect(route('admin.customer'))
            ->assertSessionHas('success', 'Customer berhasil ditambahkan.');

        $this->assertDatabaseHas('users', [
            'name' => 'Budi Customer',
            'email' => 'budi@customer.test',
            'role' => 'customer',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.supplier.store'), [
                'name' => 'PT Supplier Baru',
                'email' => 'supplier@toko.test',
                'password' => 'supplier123',
                'password_confirmation' => 'supplier123',
            ])
            ->assertRedirect(route('admin.supplier'))
            ->assertSessionHas('success', 'Supplier berhasil ditambahkan.');

        $this->assertDatabaseHas('users', [
            'name' => 'PT Supplier Baru',
            'email' => 'supplier@toko.test',
            'role' => 'supplier',
        ]);
    }
}
