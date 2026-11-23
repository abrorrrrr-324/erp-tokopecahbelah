<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanBarang;
use App\Models\PurchaseOrder;
use App\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PenerimaanBarangController extends Controller
{
    public function __construct(private StockMovementService $stockMovementService) {}

    public function index(): View
    {
        $pesanan = PurchaseOrder::with([
            'supplier',
            'user',
            'items.product',
            'penerimaanBarang',
        ])
            ->where('status', 'dikirim')
            ->latest()
            ->paginate(15);
        $pendingCount = PurchaseOrder::query()
            ->where('status', 'dikirim')
            ->where(function ($query): void {
                $query->whereDoesntHave('penerimaanBarang')
                    ->orWhereHas('penerimaanBarang', fn ($receiptQuery) => $receiptQuery
                        ->whereIn('status', ['menunggu', 'perlu_pemeriksaan']));
            })
            ->count();

        return view('gudang.penerimaan.index', compact('pesanan', 'pendingCount'));
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        abort_unless($purchaseOrder->status === 'dikirim', 404);

        $purchaseOrder->load([
            'supplier',
            'user',
            'items.product',
            'penerimaanBarang.details.produk',
            'penerimaanBarang.user',
            'penerimaanBarang.reviewedBy',
        ]);

        return view(
            'gudang.penerimaan.show',
            compact('purchaseOrder')
        );
    }

    public function foto(PenerimaanBarang $penerimaanBarang): StreamedResponse
    {
        abort_unless($penerimaanBarang->foto_bukti, 404);

        return Storage::disk('local')->response($penerimaanBarang->foto_bukti);
    }

    public function verifikasi(
        Request $request,
        PurchaseOrder $purchaseOrder
    ): RedirectResponse {
        $validated = $request->validate([
            'keputusan' => ['required', 'in:disetujui,ditolak,perlu_pemeriksaan'],
            'catatan' => ['nullable', 'string', 'max:2000'],
            'foto_bukti' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*' => ['required', 'array:jumlah_diterima,jumlah_rusak,catatan'],
            'items.*.jumlah_diterima' => ['required', 'integer', 'min:0', 'max:1000000'],
            'items.*.jumlah_rusak' => ['required', 'integer', 'min:0', 'max:1000000'],
            'items.*.catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $purchaseOrder, $request): void {
            $po = PurchaseOrder::query()
                ->whereKey($purchaseOrder->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($po->status !== 'dikirim') {
                abort(409, 'Pesanan ini tidak sedang menunggu pemeriksaan.');
            }

            $receipt = $po->penerimaanBarang()
                ->lockForUpdate()
                ->first();

            if ($receipt && $receipt->status !== 'menunggu') {
                abort(409, 'Penerimaan ini sudah pernah diperiksa.');
            }

            $orderItems = $po->items()->get();

            $this->assertSubmittedProductsMatchOrder($orderItems, $validated['items']);
            $this->assertInspectionCanBeFinalized($orderItems, $validated['items'], $validated);

            $photoPath = $this->storeEvidence($request->file('foto_bukti'));
            $receipt ??= new PenerimaanBarang([
                'purchase_order_id' => $po->id,
                'tanggal_penerimaan' => now(),
            ]);
            $receipt->fill([
                'user_id' => $request->user()->id,
                'status' => $validated['keputusan'],
                'catatan' => $validated['catatan'] ?? null,
                'foto_bukti' => $photoPath ?? $receipt->foto_bukti,
            ]);
            $receipt->save();

            foreach ($orderItems as $item) {
                $input = $validated['items'][$item->produk_id];
                $received = (int) $input['jumlah_diterima'];
                $damaged = (int) $input['jumlah_rusak'];
                $good = $received - $damaged;
                $missing = max((int) $item->jumlah - $received, 0);

                $receipt->details()->updateOrCreate(
                    ['produk_id' => $item->produk_id],
                    [
                        'jumlah' => $good,
                        'jumlah_diterima' => $received,
                        'jumlah_rusak' => $damaged,
                        'jumlah_hilang' => $missing,
                        'catatan' => $input['catatan'] ?? null,
                    ],
                );

                if ($validated['keputusan'] === 'disetujui') {
                    $this->recordReceiptStock($item->produk_id, $good, $receipt, $request);
                }
            }
        });

        return redirect()
            ->route('gudang.penerimaan')
            ->with('success', match ($validated['keputusan']) {
                'disetujui' => 'Penerimaan disetujui dan stok barang baik telah diperbarui.',
                'ditolak' => 'Penerimaan ditolak. Stok tidak mengalami perubahan.',
                default => 'Penerimaan perlu pemeriksaan lanjutan. Stok belum berubah.',
            });
    }

    public function tindakLanjut(
        Request $request,
        PenerimaanBarang $penerimaanBarang,
    ): RedirectResponse {
        $validated = $request->validate([
            'keputusan' => ['required', 'in:disetujui,ditolak'],
            'catatan' => ['required', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*' => ['required', 'array:jumlah'],
            'items.*.jumlah' => ['required', 'integer', 'min:0', 'max:1000000'],
        ]);

        DB::transaction(function () use ($validated, $penerimaanBarang, $request): void {
            $receipt = PenerimaanBarang::query()
                ->whereKey($penerimaanBarang->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($receipt->status !== 'perlu_pemeriksaan') {
                abort(409, 'Penerimaan ini tidak menunggu tindak lanjut.');
            }

            $receipt->load(['purchaseOrder.items', 'details']);
            $this->assertSubmittedProductsMatchOrder(
                $receipt->purchaseOrder->items,
                $validated['items'],
            );

            foreach ($receipt->details as $detail) {
                $approvedQuantity = (int) $validated['items'][$detail->produk_id]['jumlah'];
                $goodQuantityAvailable = (int) $detail->jumlah_diterima - (int) $detail->jumlah_rusak;

                if ($approvedQuantity > $goodQuantityAvailable) {
                    throw ValidationException::withMessages([
                        'items' => 'Jumlah barang baik tidak boleh melebihi jumlah yang diterima setelah dikurangi barang rusak.',
                    ]);
                }

                $detail->update(['jumlah' => $approvedQuantity]);

                if ($validated['keputusan'] === 'disetujui') {
                    $this->recordReceiptStock(
                        $detail->produk_id,
                        $approvedQuantity,
                        $receipt,
                        $request,
                    );
                }
            }

            $receipt->update([
                'status' => $validated['keputusan'],
                'tindak_lanjut' => $validated['catatan'],
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
            ]);
        });

        return redirect()
            ->route('gudang.penerimaan.show', $penerimaanBarang->purchase_order_id)
            ->with('success', $validated['keputusan'] === 'disetujui'
                ? 'Tindak lanjut disetujui dan stok barang baik telah diperbarui.'
                : 'Tindak lanjut ditolak. Stok tidak mengalami perubahan.');
    }

    private function assertSubmittedProductsMatchOrder(Collection $orderItems, array $items): void
    {
        if ($orderItems->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Pesanan tidak memiliki detail produk.',
            ]);
        }

        $expectedIds = $orderItems->pluck('produk_id')->map(fn ($id) => (string) $id)->sort()->values()->all();
        $submittedIds = collect(array_keys($items))->map(fn ($id) => (string) $id)->sort()->values()->all();

        if ($expectedIds !== $submittedIds) {
            throw ValidationException::withMessages([
                'items' => 'Daftar produk pemeriksaan tidak sesuai dengan pesanan.',
            ]);
        }
    }

    private function assertInspectionCanBeFinalized(Collection $orderItems, array $items, array $validated): void
    {
        $hasDiscrepancy = false;
        $hasItemNote = false;

        foreach ($orderItems as $item) {
            $input = $items[$item->produk_id];
            $received = (int) $input['jumlah_diterima'];
            $damaged = (int) $input['jumlah_rusak'];

            if ($damaged > $received) {
                throw ValidationException::withMessages([
                    'items' => 'Jumlah barang rusak tidak boleh melebihi jumlah yang diterima.',
                ]);
            }

            $hasDiscrepancy = $hasDiscrepancy
                || $received !== (int) $item->jumlah
                || $damaged > 0;
            $hasItemNote = $hasItemNote || filled($input['catatan'] ?? null);
        }

        if ($validated['keputusan'] === 'disetujui' && $hasDiscrepancy) {
            throw ValidationException::withMessages([
                'keputusan' => 'Penerimaan dengan selisih jumlah atau barang rusak harus diperiksa lebih lanjut.',
            ]);
        }

        if ($hasDiscrepancy && blank($validated['catatan'] ?? null) && ! $hasItemNote) {
            throw ValidationException::withMessages([
                'catatan' => 'Catatan wajib diisi jika terdapat selisih jumlah atau barang rusak.',
            ]);
        }
    }

    private function storeEvidence(?UploadedFile $photo): ?string
    {
        if (! $photo) {
            return null;
        }

        $path = $photo->store('warehouse/receipts', 'local');

        if (! is_string($path)) {
            throw new RuntimeException('Bukti foto penerimaan gagal disimpan.');
        }

        return $path;
    }

    private function recordReceiptStock(
        int $productId,
        int $quantity,
        PenerimaanBarang $receipt,
        Request $request,
    ): void {
        if ($quantity === 0) {
            return;
        }

        $this->stockMovementService->record(
            $productId,
            'masuk',
            $quantity,
            'penerimaan_barang',
            $receipt->id,
            (int) $request->user()->id,
            'Penerimaan barang PO-'.$receipt->purchase_order_id,
        );
    }
}
