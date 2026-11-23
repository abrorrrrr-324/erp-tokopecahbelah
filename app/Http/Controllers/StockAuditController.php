<?php

namespace App\Http\Controllers;

use App\Models\StockAudit;
use App\Models\SupplierProduct;
use App\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockAuditController extends Controller
{
    public function __construct(private StockMovementService $stockMovementService) {}

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'jenis' => ['nullable', 'in:rusak,hilang'],
            'status' => ['nullable', 'in:menunggu_pemeriksaan,disetujui,ditolak'],
            'produk_id' => ['nullable', 'integer', 'exists:produk,id'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $audits = StockAudit::query()
            ->with(['produk', 'petugas', 'reviewedBy'])
            ->when($filters['jenis'] ?? null, fn ($query, $type) => $query->where('jenis', $type))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['produk_id'] ?? null, fn ($query, $productId) => $query->where('produk_id', $productId))
            ->when($filters['tanggal_mulai'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date))
            ->latest('created_at')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('kepalagudang.audit.index', compact('audits', 'filters'));
    }

    public function create(): View
    {
        $products = SupplierProduct::query()->orderBy('nama')->get(['id', 'nama', 'sku', 'satuan', 'stok']);

        return view('kepalagudang.audit.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'produk_id' => ['required', 'integer', 'exists:produk,id'],
            'jenis' => ['required', 'in:rusak,hilang'],
            'jumlah' => ['required', 'integer', 'min:1', 'max:1000000'],
            'penyebab' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:2000'],
            'foto_bukti' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $photoPath = $this->storeEvidence($request->file('foto_bukti'));
        $audit = StockAudit::query()->create([
            'produk_id' => $validated['produk_id'],
            'user_id' => $request->user()->id,
            'jenis' => $validated['jenis'],
            'jumlah' => $validated['jumlah'],
            'penyebab' => $validated['penyebab'],
            'catatan' => $validated['catatan'] ?? null,
            'foto_bukti' => $photoPath,
            'status' => 'menunggu_pemeriksaan',
        ]);

        return redirect()
            ->route('gudang.audit.show', $audit)
            ->with('success', 'Audit berhasil dicatat dan menunggu pemeriksaan.');
    }

    public function show(StockAudit $stockAudit): View
    {
        $stockAudit->load(['produk', 'petugas', 'reviewedBy']);

        return view('kepalagudang.audit.show', ['audit' => $stockAudit]);
    }

    public function foto(StockAudit $stockAudit): StreamedResponse
    {
        abort_unless($stockAudit->foto_bukti, 404);

        return Storage::disk('local')->response($stockAudit->foto_bukti);
    }

    public function review(Request $request, StockAudit $stockAudit): RedirectResponse
    {
        $validated = $request->validate([
            'keputusan' => ['required', 'in:disetujui,ditolak'],
            'tindak_lanjut' => ['required', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($validated, $stockAudit, $request): void {
            $audit = StockAudit::query()
                ->whereKey($stockAudit->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($audit->status !== 'menunggu_pemeriksaan') {
                abort(409, 'Audit ini sudah pernah ditindaklanjuti.');
            }

            if ($validated['keputusan'] === 'disetujui') {
                $this->stockMovementService->record(
                    $audit->produk_id,
                    $audit->jenis,
                    $audit->jumlah,
                    'audit_kerusakan',
                    $audit->id,
                    (int) $request->user()->id,
                    $validated['tindak_lanjut'],
                );
            }

            $audit->update([
                'status' => $validated['keputusan'],
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'tindak_lanjut' => $validated['tindak_lanjut'],
            ]);
        });

        return redirect()
            ->route('gudang.audit.show', $stockAudit)
            ->with('success', $validated['keputusan'] === 'disetujui'
                ? 'Audit disetujui dan stok telah disesuaikan.'
                : 'Audit ditolak. Stok tidak berubah.');
    }

    private function storeEvidence(?UploadedFile $photo): ?string
    {
        if (! $photo) {
            return null;
        }

        $path = $photo->store('warehouse/audits', 'local');

        if (! is_string($path)) {
            throw new RuntimeException('Bukti foto audit gagal disimpan.');
        }

        return $path;
    }
}
