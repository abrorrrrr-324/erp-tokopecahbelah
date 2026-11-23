<?php

namespace Tests\Feature;

use App\Models\ProductCategory;
use App\Models\PurchaseOrder;
use App\Models\StockAudit;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WarehouseInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_warehouse_can_record_incoming_and_outgoing_stock_movements(): void
    {
        $warehouseManager = User::factory()->create(['role' => 'kepala_gudang']);
        $product = SupplierProduct::factory()->create(['stok' => 5]);

        $this->actingAs($warehouseManager)
            ->post(route('gudang.stok.mutasi.store'), [
                'produk_id' => $product->id,
                'jenis' => 'masuk',
                'jumlah' => 3,
                'catatan' => 'Barang retur masuk.',
            ])
            ->assertRedirect(route('gudang.stok.mutasi'))
            ->assertSessionHas('success', 'Mutasi stok berhasil dicatat dan stok telah diperbarui.');

        $this->assertDatabaseHas('produk', ['id' => $product->id, 'stok' => 8]);
        $this->assertDatabaseHas('pergerakan_stok', [
            'produk_id' => $product->id,
            'jenis' => 'masuk',
            'jumlah' => 3,
            'referensi_tipe' => 'manual',
            'user_id' => $warehouseManager->id,
        ]);

        $this->actingAs($warehouseManager)
            ->post(route('gudang.stok.mutasi.store'), [
                'produk_id' => $product->id,
                'jenis' => 'keluar',
                'jumlah' => 2,
                'catatan' => 'Dikeluarkan untuk pemenuhan pesanan.',
            ])
            ->assertRedirect(route('gudang.stok.mutasi'));

        $this->assertDatabaseHas('produk', ['id' => $product->id, 'stok' => 6]);
        $this->assertDatabaseHas('pergerakan_stok', [
            'produk_id' => $product->id,
            'jenis' => 'keluar',
            'jumlah' => 2,
        ]);

        $this->actingAs($warehouseManager)
            ->get(route('gudang.stok.mutasi'))
            ->assertOk()
            ->assertSee('Barang retur masuk.')
            ->assertSee('Dikeluarkan untuk pemenuhan pesanan.');
        $this->get(route('gudang.stok.mutasi.create'))
            ->assertOk()
            ->assertSee('Catat Mutasi Stok')
            ->assertSee($product->nama);
    }

    public function test_warehouse_cannot_record_stock_out_above_available_quantity(): void
    {
        $warehouseManager = User::factory()->create(['role' => 'kepala_gudang']);
        $product = SupplierProduct::factory()->create(['stok' => 3]);

        $this->actingAs($warehouseManager)
            ->from(route('gudang.stok.mutasi.create'))
            ->post(route('gudang.stok.mutasi.store'), [
                'produk_id' => $product->id,
                'jenis' => 'keluar',
                'jumlah' => 4,
                'catatan' => 'Pengeluaran yang melebihi stok.',
            ])
            ->assertRedirect(route('gudang.stok.mutasi.create'))
            ->assertSessionHasErrors([
                'jumlah' => 'Jumlah barang keluar melebihi stok yang tersedia.',
            ]);

        $this->assertDatabaseHas('produk', ['id' => $product->id, 'stok' => 3]);
        $this->assertDatabaseCount('pergerakan_stok', 0);
    }

    public function test_warehouse_can_record_and_approve_a_damage_audit_once(): void
    {
        $warehouseManager = User::factory()->create(['role' => 'kepala_gudang']);
        $product = SupplierProduct::factory()->create(['stok' => 9]);
        Storage::fake('local');

        $this->actingAs($warehouseManager)
            ->get(route('gudang.audit.create'))
            ->assertOk()
            ->assertSee('Catat Barang Rusak / Hilang')
            ->assertSee($product->nama);
        $this->get(route('gudang.audit'))
            ->assertOk()
            ->assertSee('Riwayat Audit Barang');

        $this->actingAs($warehouseManager)
            ->post(route('gudang.audit.store'), [
                'produk_id' => $product->id,
                'jenis' => 'rusak',
                'jumlah' => 2,
                'penyebab' => 'Pecah saat dipindahkan.',
                'catatan' => 'Ditemukan di rak bagian belakang.',
                'foto_bukti' => UploadedFile::fake()->image('audit.jpg'),
            ])
            ->assertRedirect(route('gudang.audit.show', 1))
            ->assertSessionHas('success', 'Audit berhasil dicatat dan menunggu pemeriksaan.');

        $audit = StockAudit::query()->firstOrFail();
        Storage::disk('local')->assertExists($audit->foto_bukti);
        $this->actingAs($warehouseManager)
            ->get(route('gudang.audit.foto', $audit))
            ->assertOk()
            ->assertHeader('content-type', 'image/jpeg');
        $this->assertSame('menunggu_pemeriksaan', $audit->status);
        $this->assertDatabaseHas('produk', ['id' => $product->id, 'stok' => 9]);
        $this->assertDatabaseCount('pergerakan_stok', 0);

        $this->actingAs($warehouseManager)
            ->get(route('gudang.audit.show', $audit))
            ->assertOk()
            ->assertSee($product->nama)
            ->assertSee('Pemeriksaan dan Tindak Lanjut');

        $this->actingAs($warehouseManager)
            ->post(route('gudang.audit.review', $audit), [
                'keputusan' => 'disetujui',
                'tindak_lanjut' => 'Barang rusak disetujui dan dikeluarkan dari stok.',
            ])
            ->assertRedirect(route('gudang.audit.show', $audit))
            ->assertSessionHas('success', 'Audit disetujui dan stok telah disesuaikan.');

        $this->assertDatabaseHas('stock_audits', [
            'id' => $audit->id,
            'status' => 'disetujui',
            'reviewed_by' => $warehouseManager->id,
        ]);
        $this->assertDatabaseHas('produk', ['id' => $product->id, 'stok' => 7]);
        $this->assertDatabaseHas('pergerakan_stok', [
            'produk_id' => $product->id,
            'jenis' => 'rusak',
            'jumlah' => 2,
            'referensi_tipe' => 'audit_kerusakan',
            'referensi_id' => $audit->id,
        ]);

        $this->actingAs($warehouseManager)
            ->post(route('gudang.audit.review', $audit), [
                'keputusan' => 'disetujui',
                'tindak_lanjut' => 'Pemeriksaan ulang.',
            ])
            ->assertStatus(409);

        $this->assertDatabaseHas('produk', ['id' => $product->id, 'stok' => 7]);
        $this->assertDatabaseCount('pergerakan_stok', 1);
    }

    public function test_damage_audit_cannot_reduce_stock_below_zero(): void
    {
        $warehouseManager = User::factory()->create(['role' => 'kepala_gudang']);
        $product = SupplierProduct::factory()->create(['stok' => 2]);
        $audit = StockAudit::query()->create([
            'produk_id' => $product->id,
            'user_id' => $warehouseManager->id,
            'jenis' => 'hilang',
            'jumlah' => 3,
            'penyebab' => 'Selisih pemeriksaan fisik.',
            'status' => 'menunggu_pemeriksaan',
        ]);

        $this->actingAs($warehouseManager)
            ->from(route('gudang.audit.show', $audit))
            ->post(route('gudang.audit.review', $audit), [
                'keputusan' => 'disetujui',
                'tindak_lanjut' => 'Tidak dapat disetujui karena stok tidak mencukupi.',
            ])
            ->assertRedirect(route('gudang.audit.show', $audit))
            ->assertSessionHasErrors('jumlah');

        $this->assertDatabaseHas('stock_audits', [
            'id' => $audit->id,
            'status' => 'menunggu_pemeriksaan',
        ]);
        $this->assertDatabaseHas('produk', ['id' => $product->id, 'stok' => 2]);
        $this->assertDatabaseCount('pergerakan_stok', 0);
    }

    public function test_non_warehouse_users_cannot_record_stock_movements_or_audits(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $product = SupplierProduct::factory()->create(['stok' => 5]);

        $this->actingAs($customer)
            ->post(route('gudang.stok.mutasi.store'), [
                'produk_id' => $product->id,
                'jenis' => 'keluar',
                'jumlah' => 1,
                'catatan' => 'Tidak diizinkan.',
            ])
            ->assertRedirect(route('login'));

        $this->actingAs($customer)
            ->post(route('gudang.audit.store'), [
                'produk_id' => $product->id,
                'jenis' => 'hilang',
                'jumlah' => 1,
                'penyebab' => 'Tidak diizinkan.',
            ])
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('produk', ['id' => $product->id, 'stok' => 5]);
        $this->assertDatabaseCount('stock_audits', 0);
        $this->assertDatabaseCount('pergerakan_stok', 0);
    }

    public function test_warehouse_dashboard_displays_live_inventory_metrics_and_activity(): void
    {
        $warehouseManager = User::factory()->create(['role' => 'kepala_gudang']);
        $product = SupplierProduct::factory()->create([
            'nama' => 'Piring Dashboard',
            'stok' => 5,
        ]);
        $purchaseOrder = PurchaseOrder::factory()->create(['status' => 'dikirim']);

        $this->actingAs($warehouseManager)
            ->post(route('gudang.stok.mutasi.store'), [
                'produk_id' => $product->id,
                'jenis' => 'masuk',
                'jumlah' => 4,
                'catatan' => 'Barang masuk dashboard.',
            ])
            ->assertRedirect();
        $this->post(route('gudang.stok.mutasi.store'), [
            'produk_id' => $product->id,
            'jenis' => 'keluar',
            'jumlah' => 2,
            'catatan' => 'Barang keluar dashboard.',
        ])->assertRedirect();
        StockAudit::query()->create([
            'produk_id' => $product->id,
            'user_id' => $warehouseManager->id,
            'jenis' => 'rusak',
            'jumlah' => 1,
            'penyebab' => 'Retak.',
            'status' => 'disetujui',
        ]);

        $response = $this->get(route('gudang.dashboard'))->assertOk();
        $response->assertViewHas('stats', function (array $stats) use ($purchaseOrder): bool {
            $this->assertSame(1, $stats['totalProduk']);
            $this->assertSame(7, $stats['totalStok']);
            $this->assertSame(4, $stats['barangMasukHariIni']);
            $this->assertSame(2, $stats['barangKeluarHariIni']);
            $this->assertSame(1, $stats['totalBarangRusak']);
            $this->assertSame(1, $stats['menungguVerifikasi']);

            return $purchaseOrder->status === 'dikirim';
        });
        $response->assertViewHas('chart', fn ($chart): bool => $chart->sum('masuk') === 4 && $chart->sum('keluar') === 2);
        $response->assertSee('Piring Dashboard')->assertSee('Barang masuk dashboard.');
    }

    public function test_reports_filter_by_category_and_transaction_type_and_export_pdf_and_excel(): void
    {
        $warehouseManager = User::factory()->create(['role' => 'kepala_gudang']);
        $category = ProductCategory::query()->create([
            'nama' => 'Peralatan Rumah',
            'status' => 'aktif',
        ]);
        $otherCategory = ProductCategory::query()->create([
            'nama' => 'Dekorasi',
            'status' => 'aktif',
        ]);
        $product = SupplierProduct::factory()->create([
            'nama' => 'Gelas Laporan',
            'category_id' => $category->id,
            'stok' => 10,
        ]);
        $otherProduct = SupplierProduct::factory()->create([
            'nama' => 'Vas Laporan',
            'category_id' => $otherCategory->id,
        ]);
        $filters = [
            'category_id' => $category->id,
            'jenis' => 'masuk',
            'tanggal_mulai' => today()->toDateString(),
            'tanggal_selesai' => today()->toDateString(),
        ];

        $this->actingAs($warehouseManager)
            ->post(route('gudang.stok.mutasi.store'), [
                'produk_id' => $product->id,
                'jenis' => 'masuk',
                'jumlah' => 2,
                'catatan' => 'Mutasi yang masuk laporan.',
            ])
            ->assertRedirect();
        $this->post(route('gudang.stok.mutasi.store'), [
            'produk_id' => $otherProduct->id,
            'jenis' => 'keluar',
            'jumlah' => 1,
            'catatan' => 'Mutasi di luar filter.',
        ])->assertRedirect();

        $this->get(route('gudang.laporan', $filters))
            ->assertOk()
            ->assertSee('Gelas Laporan')
            ->assertSee('Mutasi yang masuk laporan.')
            ->assertDontSee('Mutasi di luar filter.');

        $this->get(route('gudang.laporan.print', $filters))
            ->assertOk()
            ->assertSee('Cetak Laporan')
            ->assertSee('Gelas Laporan')
            ->assertDontSee('Vas Laporan');

        $this->get(route('gudang.laporan.pdf', $filters))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->get(route('gudang.laporan.excel', $filters))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
