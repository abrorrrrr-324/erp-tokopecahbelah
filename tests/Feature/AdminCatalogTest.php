<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
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

    public function test_admin_catalog_confirmation_messages_use_logout_style_copy(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $product = Product::create([
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
            ->assertSee('Apakah anda yakin ingin menghapus produk Kursi Kayu Minimalis?');

        $this->actingAs($admin)
            ->get(route('admin.katalog.edit', $product))
            ->assertOk()
            ->assertSee('Apakah anda yakin ingin mengubah data produk ini?');
    }

    public function test_admin_can_search_products_by_name_category_or_status(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Product::create([
            'name' => 'Kursi Kayu Minimalis',
            'category' => 'Furniture',
            'stock' => 12,
            'price' => 800000,
            'status' => 'Aktif',
            'created_by' => $admin->id,
        ]);

        Product::create([
            'name' => 'Lemari Kayu Besar',
            'category' => 'Furniture',
            'stock' => 9,
            'price' => 1200000,
            'status' => 'Hampir habis',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.katalog', ['search' => 'Kursi']))
            ->assertOk()
            ->assertSee('Kursi Kayu Minimalis')
            ->assertDontSee('Lemari Kayu Besar');

        $this->actingAs($admin)
            ->get(route('admin.katalog', ['search' => 'Hampir habis']))
            ->assertOk()
            ->assertSee('Lemari Kayu Besar');
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

        $customer = User::query()->where('email', 'budi@customer.test')->firstOrFail();
        $this->assertTrue(Hash::check('password123', $customer->password));

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

    public function test_admin_update_for_customer_and_supplier_uses_catalog_style_redirects(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $customer = User::factory()->create([
            'name' => 'Customer Lama',
            'email' => 'lama@customer.test',
            'role' => 'customer',
        ]);

        $supplier = User::factory()->create([
            'name' => 'Supplier Lama',
            'email' => 'lama@supplier.test',
            'role' => 'supplier',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.customer.update', $customer), [
                'name' => 'Customer Baru',
                'email' => 'baru@customer.test',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ])
            ->assertRedirect(route('admin.customer'))
            ->assertSessionHas('success', 'Data customer berhasil diperbarui.');

        $this->assertTrue(Hash::check('newpassword123', $customer->fresh()->password));

        $this->actingAs($admin)
            ->put(route('admin.supplier.update', $supplier), [
                'name' => 'Supplier Baru',
                'email' => 'baru@supplier.test',
                'password' => 'newsupplier123',
                'password_confirmation' => 'newsupplier123',
            ])
            ->assertRedirect(route('admin.supplier'))
            ->assertSessionHas('success', 'Data supplier berhasil diperbarui.');

        $this->assertTrue(Hash::check('newsupplier123', $supplier->fresh()->password));
    }
}
