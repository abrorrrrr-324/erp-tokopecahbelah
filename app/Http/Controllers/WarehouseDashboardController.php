<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanBarang;
use App\Models\PergerakanStok;
use App\Models\PurchaseOrder;
use App\Models\StockAudit;
use App\Models\SupplierProduct;
use Illuminate\View\View;

class WarehouseDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'totalProduk' => SupplierProduct::query()->count(),
            'totalStok' => (int) SupplierProduct::query()->sum('stok'),
            'barangMasukHariIni' => (int) PergerakanStok::query()
                ->where('jenis', 'masuk')
                ->whereDate('created_at', today())
                ->sum('jumlah'),
            'barangKeluarHariIni' => (int) PergerakanStok::query()
                ->whereIn('jenis', ['keluar', 'rusak', 'hilang'])
                ->whereDate('created_at', today())
                ->sum('jumlah'),
            'totalBarangRusak' => (int) StockAudit::query()
                ->where('jenis', 'rusak')
                ->where('status', 'disetujui')
                ->sum('jumlah'),
            'menungguVerifikasi' => PenerimaanBarang::query()
                ->whereIn('status', ['menunggu', 'perlu_pemeriksaan'])
                ->count() + PurchaseOrder::query()
                ->where('status', 'dikirim')
                ->whereDoesntHave('penerimaanBarang')
                ->count(),
        ];

        $chartStart = today()->subDays(6);
        $movementRows = PergerakanStok::query()
            ->whereDate('created_at', '>=', $chartStart)
            ->whereIn('jenis', ['masuk', 'keluar', 'rusak', 'hilang'])
            ->selectRaw('DATE(created_at) as tanggal, jenis, SUM(jumlah) as jumlah')
            ->groupBy('tanggal', 'jenis')
            ->get();
        $movementMap = $movementRows->mapWithKeys(
            fn ($row): array => [$row->tanggal.'|'.$row->jenis => (int) $row->jumlah],
        );
        $chart = collect(range(6, 0))->map(function (int $daysAgo) use ($movementMap): array {
            $date = today()->subDays($daysAgo);
            $dateKey = $date->toDateString();

            return [
                'label' => $date->format('d/m'),
                'masuk' => $movementMap->get($dateKey.'|masuk', 0),
                'keluar' => $movementMap->get($dateKey.'|keluar', 0)
                    + $movementMap->get($dateKey.'|rusak', 0)
                    + $movementMap->get($dateKey.'|hilang', 0),
            ];
        });
        $chartMaximum = max(1, (int) $chart->max(fn (array $day): int => max($day['masuk'], $day['keluar'])));

        $movementActivities = PergerakanStok::query()
            ->with(['produk:id,nama,satuan', 'user:id,name'])
            ->latest('created_at')
            ->latest('id')
            ->limit(8)
            ->get()
            ->map(fn (PergerakanStok $movement): array => [
                'date' => $movement->created_at,
                'product' => $movement->produk?->nama ?? '-',
                'type' => 'Mutasi '.str_replace('_', ' ', $movement->jenis),
                'quantity' => $movement->jumlah.' '.($movement->produk?->satuan ?? 'unit'),
                'person' => $movement->user?->name ?? '-',
                'notes' => $movement->catatan ?: '-',
            ]);
        $auditActivities = StockAudit::query()
            ->with(['produk:id,nama,satuan', 'petugas:id,name'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn (StockAudit $audit): array => [
                'date' => $audit->created_at,
                'product' => $audit->produk?->nama ?? '-',
                'type' => 'Audit '.str_replace('_', ' ', $audit->jenis).' ('.str_replace('_', ' ', $audit->status).')',
                'quantity' => $audit->jumlah.' '.($audit->produk?->satuan ?? 'unit'),
                'person' => $audit->petugas?->name ?? '-',
                'notes' => $audit->penyebab,
            ]);
        $activities = $movementActivities
            ->concat($auditActivities)
            ->sortByDesc('date')
            ->take(10)
            ->values();

        return view('kepalagudang.dashboard', compact('stats', 'chart', 'chartMaximum', 'activities'));
    }
}
