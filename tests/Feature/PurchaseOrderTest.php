<?php

namespace Tests\Feature;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_purchase_order_from_the_selected_supplier_catalog(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $supplier = Supplier::factory()->create();
        $product = SupplierProduct::factory()->create([
            'supplier_id' => $supplier->id,
            'nama' => 'Gelas Kaca Milik',
            'harga_beli' => 12500,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.pembelian.store'), [
                'supplier_id' => $supplier->id,
                'catatan' => 'Kirim sebelum akhir minggu.',
                'items' => [
                    ['product_id' => $product->id, 'jumlah' => 3],
                ],
            ])
            ->assertRedirect(route('admin.pembelian.show', 1))
            ->assertSessionHas('success', 'Pesanan pembelian berhasil dibuat.');

        $order = PurchaseOrder::query()->with('items')->firstOrFail();
        $this->assertDatabaseHas('pesanan_pembelian', [
            'id' => $order->id,
            'supplier_id' => $supplier->id,
            'user_id' => $admin->id,
            'total' => 37500,
            'status' => 'menunggu',
            'catatan' => 'Kirim sebelum akhir minggu.',
        ]);
        $this->assertDatabaseHas('detail_pembelian', [
            'purchase_order_id' => $order->id,
            'produk_id' => $product->id,
            'jumlah' => 3,
            'harga' => 12500,
            'sub_total' => 37500,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pembelian'))
            ->assertOk()
            ->assertSee('PO-00001')
            ->assertSee('Rp 37.500');

        $this->actingAs($admin)
            ->get(route('admin.pembelian.show', $order))
            ->assertOk()
            ->assertSee('Gelas Kaca Milik')
            ->assertSee('PO-00001');
    }

    public function test_admin_can_render_a_purchase_order_form_with_supplier_catalog_products(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $supplier = Supplier::factory()->create(['nama' => 'Supplier Peralatan']);
        SupplierProduct::factory()->create([
            'supplier_id' => $supplier->id,
            'nama' => 'Gelas Katalog Supplier',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pembelian.create'))
            ->assertOk()
            ->assertSee('Supplier Peralatan')
            ->assertSee('Gelas Katalog Supplier');
    }

    public function test_supplier_created_by_admin_is_available_for_purchase_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.supplier.store'), [
                'name' => 'PT Katalog Rumah',
                'email' => 'katalog@rumah.test',
                'password' => 'supplier123',
                'password_confirmation' => 'supplier123',
            ])
            ->assertRedirect(route('admin.supplier'));

        $supplierUser = User::query()->where('email', 'katalog@rumah.test')->firstOrFail();
        $supplier = Supplier::query()->where('user_id', $supplierUser->id)->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.pembelian.create'))
            ->assertOk()
            ->assertSee($supplier->nama);
    }

    public function test_admin_cannot_order_a_product_from_another_supplier_catalog(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $supplier = Supplier::factory()->create();
        $otherSupplier = Supplier::factory()->create();
        $otherProduct = SupplierProduct::factory()->create([
            'supplier_id' => $otherSupplier->id,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.pembelian.create'))
            ->post(route('admin.pembelian.store'), [
                'supplier_id' => $supplier->id,
                'items' => [
                    ['product_id' => $otherProduct->id, 'jumlah' => 1],
                ],
            ])
            ->assertRedirect(route('admin.pembelian.create'))
            ->assertSessionHasErrors('items.0.product_id');

        $this->assertDatabaseCount('pesanan_pembelian', 0);
        $this->assertDatabaseCount('detail_pembelian', 0);
    }

    public function test_invalid_supplier_input_is_rejected_without_database_changes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $supplier = Supplier::factory()->create();
        $product = SupplierProduct::factory()->create([
            'supplier_id' => $supplier->id,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.pembelian.create'))
            ->post(route('admin.pembelian.store'), [
                'supplier_id' => ['invalid'],
                'items' => [
                    ['product_id' => $product->id, 'jumlah' => 1],
                ],
            ])
            ->assertRedirect(route('admin.pembelian.create'))
            ->assertSessionHasErrors('supplier_id');

        $this->assertDatabaseCount('pesanan_pembelian', 0);
    }

    public function test_supplier_sees_only_purchase_orders_addressed_to_its_profile(): void
    {
        $supplier = Supplier::factory()->create();
        $otherSupplier = Supplier::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = PurchaseOrder::factory()->create([
            'supplier_id' => $supplier->id,
            'user_id' => $admin->id,
        ]);
        $otherOrder = PurchaseOrder::factory()->create([
            'supplier_id' => $otherSupplier->id,
            'user_id' => $admin->id,
        ]);

        $this->actingAs($supplier->user)
            ->get(route('supplier.pesanan'))
            ->assertOk()
            ->assertSee('PO-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT))
            ->assertDontSee('PO-'.str_pad((string) $otherOrder->id, 5, '0', STR_PAD_LEFT));

        $this->actingAs($supplier->user)
            ->get(route('supplier.dashboard'))
            ->assertOk()
            ->assertSee('Total Pesanan')
            ->assertSee('PO-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT));
    }

    public function test_supplier_can_view_the_details_of_its_purchase_order(): void
    {
        $supplier = Supplier::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $product = SupplierProduct::factory()->create([
            'supplier_id' => $supplier->id,
            'nama' => 'Gelas Kaca Detail',
        ]);
        $order = PurchaseOrder::factory()->create([
            'supplier_id' => $supplier->id,
            'user_id' => $admin->id,
            'catatan' => 'Mohon dikirim dengan aman.',
        ]);
        PurchaseOrderItem::query()->create([
            'purchase_order_id' => $order->id,
            'produk_id' => $product->id,
            'jumlah' => 4,
            'harga' => 12500,
            'sub_total' => 50000,
        ]);

        $this->actingAs($supplier->user)
            ->get(route('supplier.pesanan.show', $order))
            ->assertOk()
            ->assertSee('PO-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT))
            ->assertSee('Gelas Kaca Detail')
            ->assertSee('Mohon dikirim dengan aman.')
            ->assertSee('Konfirmasi pesanan');
    }

    public function test_supplier_can_confirm_its_waiting_purchase_order(): void
    {
        $supplier = Supplier::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = PurchaseOrder::factory()->create([
            'supplier_id' => $supplier->id,
            'user_id' => $admin->id,
            'status' => 'menunggu',
        ]);

        $this->actingAs($supplier->user)
            ->post(route('supplier.pesanan.confirm', $order))
            ->assertRedirect(route('supplier.pesanan.show', $order))
            ->assertSessionHas('success', 'Pesanan pembelian berhasil dikonfirmasi.');

        $this->assertDatabaseHas('pesanan_pembelian', [
            'id' => $order->id,
            'status' => 'dikonfirmasi',
        ]);
    }

    public function test_supplier_cannot_view_another_suppliers_purchase_order(): void
    {
        $supplier = Supplier::factory()->create();
        $otherSupplier = Supplier::factory()->create();
        $order = PurchaseOrder::factory()->create([
            'supplier_id' => $otherSupplier->id,
        ]);

        $this->actingAs($supplier->user)
            ->get(route('supplier.pesanan.show', $order))
            ->assertNotFound();
    }

    public function test_supplier_cannot_confirm_another_suppliers_purchase_order(): void
    {
        $supplier = Supplier::factory()->create();
        $otherSupplier = Supplier::factory()->create();
        $order = PurchaseOrder::factory()->create([
            'supplier_id' => $otherSupplier->id,
            'status' => 'menunggu',
        ]);

        $this->actingAs($supplier->user)
            ->post(route('supplier.pesanan.confirm', $order))
            ->assertNotFound();

        $this->assertDatabaseHas('pesanan_pembelian', [
            'id' => $order->id,
            'status' => 'menunggu',
        ]);
    }

    public function test_supplier_cannot_confirm_a_purchase_order_that_is_not_waiting(): void
    {
        $supplier = Supplier::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = PurchaseOrder::factory()->create([
            'supplier_id' => $supplier->id,
            'user_id' => $admin->id,
            'status' => 'dikonfirmasi',
        ]);

        $this->actingAs($supplier->user)
            ->post(route('supplier.pesanan.confirm', $order))
            ->assertRedirect(route('supplier.pesanan.show', $order))
            ->assertSessionHas('error', 'Pesanan hanya dapat dikonfirmasi saat status menunggu.');

        $this->assertDatabaseHas('pesanan_pembelian', [
            'id' => $order->id,
            'status' => 'dikonfirmasi',
        ]);
    }

    public function test_customer_cannot_confirm_a_supplier_purchase_order(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = PurchaseOrder::factory()->create();

        $this->actingAs($customer)
            ->post(route('supplier.pesanan.confirm', $order))
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('pesanan_pembelian', [
            'id' => $order->id,
            'status' => 'menunggu',
        ]);
    }

    public function test_customer_cannot_create_a_purchase_order(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->post(route('admin.pembelian.store'), [])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('pesanan_pembelian', 0);
    }
}
