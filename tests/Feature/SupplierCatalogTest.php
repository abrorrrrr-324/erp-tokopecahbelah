<?php

namespace Tests\Feature;

use App\Models\ProductCategory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_sees_only_its_own_catalog(): void
    {
        $supplier = Supplier::factory()->create();
        $otherSupplier = Supplier::factory()->create();
        SupplierProduct::factory()->create([
            'supplier_id' => $supplier->id,
            'nama' => 'Gelas Kaca Milik Saya',
        ]);
        SupplierProduct::factory()->create([
            'supplier_id' => $otherSupplier->id,
            'nama' => 'Piring Supplier Lain',
        ]);

        $this->actingAs($supplier->user)
            ->get(route('supplier.katalog'))
            ->assertOk()
            ->assertSee('Gelas Kaca Milik Saya')
            ->assertDontSee('Piring Supplier Lain');
    }

    public function test_supplier_can_add_a_product_to_its_catalog(): void
    {
        $user = User::factory()->create(['role' => 'supplier']);
        $category = ProductCategory::query()->create([
            'nama' => 'Peralatan Rumah',
            'status' => 'aktif',
        ]);

        $this->actingAs($user)
            ->post(route('supplier.katalog.store'), [
                'sku' => 'SUP-NEW-001',
                'nama' => 'Botol Minum',
                'category_id' => $category->id,
                'deskripsi' => 'Botol minum berbahan kaca.',
                'harga_beli' => 14500,
                'satuan' => 'pcs',
                'stok' => 80,
                'status' => 'aktif',
            ])
            ->assertRedirect(route('supplier.katalog'))
            ->assertSessionHas('success', 'Produk katalog berhasil ditambahkan.');

        $supplier = Supplier::query()->where('user_id', $user->id)->firstOrFail();
        $this->assertDatabaseHas('produk', [
            'supplier_id' => $supplier->id,
            'sku' => 'SUP-NEW-001',
            'nama' => 'Botol Minum',
            'harga_beli' => 14500,
        ]);
    }

    public function test_different_suppliers_can_use_the_same_sku(): void
    {
        $firstSupplier = Supplier::factory()->create();
        $secondSupplier = Supplier::factory()->create();
        $category = ProductCategory::query()->create([
            'nama' => 'Peralatan Makan',
            'status' => 'aktif',
        ]);
        SupplierProduct::factory()->create([
            'supplier_id' => $firstSupplier->id,
            'sku' => 'ITEM-001',
        ]);

        $this->actingAs($secondSupplier->user)
            ->post(route('supplier.katalog.store'), [
                'sku' => 'ITEM-001',
                'nama' => 'Produk Supplier Kedua',
                'category_id' => $category->id,
                'harga_beli' => 10000,
                'satuan' => 'pcs',
                'stok' => 20,
                'status' => 'aktif',
            ])
            ->assertRedirect(route('supplier.katalog'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('produk', [
            'supplier_id' => $secondSupplier->id,
            'sku' => 'ITEM-001',
            'nama' => 'Produk Supplier Kedua',
        ]);
    }

    public function test_supplier_can_update_its_own_catalog_product(): void
    {
        $supplier = Supplier::factory()->create();
        $category = ProductCategory::query()->create([
            'nama' => 'Peralatan Rumah',
            'status' => 'aktif',
        ]);
        $product = SupplierProduct::factory()->create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'sku' => 'SUP-UPDATE-001',
        ]);

        $this->actingAs($supplier->user)
            ->put(route('supplier.katalog.update', $product->id), [
                'sku' => 'SUP-UPDATE-001',
                'nama' => 'Produk Diperbarui',
                'category_id' => $category->id,
                'harga_beli' => 25000,
                'satuan' => 'box',
                'stok' => 40,
                'status' => 'aktif',
            ])
            ->assertRedirect(route('supplier.katalog'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('produk', [
            'id' => $product->id,
            'supplier_id' => $supplier->id,
            'nama' => 'Produk Diperbarui',
            'harga_beli' => 25000,
            'satuan' => 'box',
        ]);
    }

    public function test_supplier_can_render_create_and_edit_forms_for_its_products(): void
    {
        $supplier = Supplier::factory()->create();
        $category = ProductCategory::query()->create([
            'nama' => 'Dapur',
            'status' => 'aktif',
        ]);
        $product = SupplierProduct::factory()->create([
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'nama' => 'Piring Keramik',
        ]);

        $this->actingAs($supplier->user)
            ->get(route('supplier.katalog.create'))
            ->assertOk()
            ->assertSee('Tambah Produk')
            ->assertSee('Dapur');

        $this->actingAs($supplier->user)
            ->get(route('supplier.katalog.edit', $product->id))
            ->assertOk()
            ->assertSee('Piring Keramik')
            ->assertSee('Simpan Perubahan');
    }

    public function test_supplier_cannot_edit_another_suppliers_product(): void
    {
        $supplier = Supplier::factory()->create();
        $otherSupplier = Supplier::factory()->create();
        $product = SupplierProduct::factory()->create([
            'supplier_id' => $otherSupplier->id,
        ]);

        $this->actingAs($supplier->user)
            ->get(route('supplier.katalog.edit', $product->id))
            ->assertNotFound();
    }

    public function test_supplier_cannot_delete_a_product_used_by_a_purchase_order(): void
    {
        $supplier = Supplier::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $product = SupplierProduct::factory()->create([
            'supplier_id' => $supplier->id,
        ]);
        $order = PurchaseOrder::factory()->create([
            'supplier_id' => $supplier->id,
            'user_id' => $admin->id,
        ]);
        PurchaseOrderItem::query()->create([
            'purchase_order_id' => $order->id,
            'produk_id' => $product->id,
            'jumlah' => 2,
            'harga' => 10000,
            'sub_total' => 20000,
        ]);

        $this->actingAs($supplier->user)
            ->delete(route('supplier.katalog.destroy', $product->id))
            ->assertRedirect(route('supplier.katalog'))
            ->assertSessionHas('error');

        $this->assertModelExists($product);
    }

    public function test_admin_is_redirected_from_supplier_catalog(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('supplier.katalog'))
            ->assertRedirect(route('login'));
    }
}
