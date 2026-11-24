<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PenerimaanBarang;
use App\Models\PergerakanStok;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenerimaanBarangController extends Controller
{
    /**
     * Daftar pesanan yang sudah dikirim Supplier.
     */
    public function index()
    {
        $pesanan = PurchaseOrder::with([
            'supplier',
            'user',
            'items.product',
        ])
            ->where('status', 'dikirim')
            ->whereDoesntHave('penerimaanBarang')
            ->latest()
            ->get();

        return view('gudang.penerimaan.index', compact('pesanan'));
    }

    /**
     * Detail pesanan untuk pemeriksaan Kepala Gudang.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        abort_unless($purchaseOrder->status === 'dikirim', 404);

        abort_if(
            $purchaseOrder->penerimaanBarang()->exists(),
            404
        );

        $purchaseOrder->load([
            'supplier',
            'user',
            'items.product',
        ]);

        return view(
            'gudang.penerimaan.show',
            compact('purchaseOrder')
        );
    }

    /**
     * Menyimpan hasil pemeriksaan dan memperbarui stok.
     */
    public function verifikasi(
        Request $request,
        PurchaseOrder $purchaseOrder
    ) {
        $validated = $request->validate([
            'keputusan' => 'required|in:diterima,ditolak',
            'catatan' => 'nullable|string|max:2000',

            'items' => 'required|array',
            'items.*.jumlah' => 'required|integer|min:0',
            'items.*.catatan' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use (
            $validated,
            $purchaseOrder
        ) {
            // Kunci PO agar tidak diverifikasi bersamaan.
            $po = PurchaseOrder::query()
                ->whereKey($purchaseOrder->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($po->status !== 'dikirim') {
                abort(409, 'Pesanan ini tidak sedang menunggu pemeriksaan.');
            }

            // Cegah penerimaan ganda.
            if ($po->penerimaanBarang()->exists()) {
                abort(409, 'Pesanan ini sudah pernah diverifikasi.');
            }

            $orderItems = $po->items()->get();

            if ($orderItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'Pesanan tidak memiliki detail produk.',
                ]);
            }

            // Pastikan produk yang diperiksa sama dengan produk PO.
            $expectedIds = $orderItems
                ->pluck('produk_id')
                ->map(fn ($id) => (string) $id)
                ->sort()
                ->values()
                ->all();

            $submittedIds = collect(array_keys($validated['items']))
                ->map(fn ($id) => (string) $id)
                ->sort()
                ->values()
                ->all();

            if ($expectedIds !== $submittedIds) {
                throw ValidationException::withMessages([
                    'items' => 'Daftar produk pemeriksaan tidak sesuai dengan pesanan.',
                ]);
            }

            // Jika diterima, seluruh jumlah harus sesuai pesanan.
            if ($validated['keputusan'] === 'diterima') {
                foreach ($orderItems as $item) {
                    $jumlahDiterima = (int) $validated['items'][$item->produk_id]['jumlah'];

                    if ($jumlahDiterima !== (int) $item->jumlah) {
                        throw ValidationException::withMessages([
                            'items' => 'Jumlah barang tidak sesuai. Periksa kembali atau pilih keputusan ditolak.',
                        ]);
                    }
                }
            }

            // Simpan data penerimaan.
            $penerimaan = PenerimaanBarang::create([
                'user_id' => auth()->id(),
                'purchase_order_id' => $po->id,
                'tanggal_penerimaan' => now(),
                'status' => $validated['keputusan'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Simpan detail hasil pemeriksaan.
            foreach ($orderItems as $item) {
                $produkId = $item->produk_id;

                $jumlah = (int) $validated['items'][$produkId]['jumlah'];

                $catatanItem = $validated['items'][$produkId]['catatan'] ?? null;

                $penerimaan->details()->create([
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                    'catatan' => $catatanItem,
                ]);

                // Stok hanya bertambah jika penerimaan disetujui.
                if ($validated['keputusan'] === 'diterima') {
                    $produk = SupplierProduct::query()
                        ->whereKey($produkId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $produk->increment('stok', $jumlah);

                    // Catat pergerakan stok masuk.
                    PergerakanStok::create([
                        'produk_id' => $produkId,
                        'jenis' => 'masuk',
                        'jumlah' => $jumlah,
                        'referensi_tipe' => 'penerimaan_barang',
                        'referensi_id' => $penerimaan->id,
                        'user_id' => auth()->id(),
                        'catatan' => 'Penerimaan barang PO-' . $po->id,
                    ]);
                }
            }
        });

        if ($validated['keputusan'] === 'diterima') {
            return redirect()
                ->route('gudang.penerimaan')
                ->with('success', 'Barang berhasil diterima dan stok telah diperbarui.');
        }

        return redirect()
            ->route('gudang.penerimaan')
            ->with('success', 'Penerimaan barang ditolak. Stok tidak mengalami perubahan.');
    }
}
