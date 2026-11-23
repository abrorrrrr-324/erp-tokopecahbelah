<?php

namespace App\Exports;

use App\Models\PergerakanStok;
use App\WarehouseReportQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class WarehouseMovementExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private WarehouseReportQuery $reportQuery,
        private array $filters,
    ) {}

    public function query(): Builder
    {
        return $this->reportQuery->movements($this->filters);
    }

    public function headings(): array
    {
        return ['Tanggal', 'SKU', 'Produk', 'Kategori', 'Jenis', 'Jumlah', 'Sumber', 'Petugas', 'Catatan'];
    }

    public function map(mixed $row): array
    {
        if (! $row instanceof PergerakanStok) {
            return [];
        }

        return [
            $row->created_at?->format('Y-m-d H:i:s'),
            $row->produk?->sku,
            $row->produk?->nama,
            $row->produk?->category?->nama,
            $row->jenis,
            $row->jumlah,
            $row->referensi_tipe,
            $row->user?->name,
            $row->catatan,
        ];
    }

    public function title(): string
    {
        return 'Mutasi Barang';
    }
}
