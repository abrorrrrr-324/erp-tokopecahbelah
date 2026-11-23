<?php

namespace App\Http\Controllers;

use App\Models\PergerakanStok;
use App\Models\SupplierProduct;
use App\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function __construct(private StockMovementService $stockMovementService) {}

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'produk_id' => ['nullable', 'integer', 'exists:produk,id'],
            'jenis' => ['nullable', 'in:masuk,keluar,rusak,hilang'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $movements = PergerakanStok::query()
            ->with(['produk.category', 'user'])
            ->when($filters['produk_id'] ?? null, fn ($query, $productId) => $query->where('produk_id', $productId))
            ->when($filters['jenis'] ?? null, fn ($query, $type) => $query->where('jenis', $type))
            ->when($filters['tanggal_mulai'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date))
            ->latest('created_at')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $products = SupplierProduct::query()->orderBy('nama')->get(['id', 'nama', 'sku']);

        return view('kepalagudang.mutasi.index', compact('movements', 'products', 'filters'));
    }

    public function create(): View
    {
        $products = SupplierProduct::query()->orderBy('nama')->get(['id', 'nama', 'sku', 'satuan', 'stok']);

        return view('kepalagudang.mutasi.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'produk_id' => ['required', 'integer', 'exists:produk,id'],
            'jenis' => ['required', 'in:masuk,keluar'],
            'jumlah' => ['required', 'integer', 'min:1', 'max:1000000'],
            'catatan' => ['required', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($validated, $request): void {
            $this->stockMovementService->record(
                (int) $validated['produk_id'],
                $validated['jenis'],
                (int) $validated['jumlah'],
                'manual',
                null,
                (int) $request->user()->id,
                $validated['catatan'],
            );
        });

        return redirect()
            ->route('gudang.stok.mutasi')
            ->with('success', 'Mutasi stok berhasil dicatat dan stok telah diperbarui.');
    }
}
