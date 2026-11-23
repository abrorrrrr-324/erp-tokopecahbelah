<?php

namespace App\Exports;

use App\Models\StockAudit;
use App\WarehouseReportQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class WarehouseAuditExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private WarehouseReportQuery $reportQuery,
        private array $filters,
    ) {}

    public function query(): Builder
    {
        return $this->reportQuery->audits($this->filters);
    }

    public function headings(): array
    {
        return ['Tanggal', 'SKU', 'Produk', 'Kategori', 'Jenis', 'Jumlah', 'Penyebab', 'Status', 'Petugas', 'Tindak Lanjut'];
    }

    public function map(mixed $row): array
    {
        if (! $row instanceof StockAudit) {
            return [];
        }

        return [
            $row->created_at?->format('Y-m-d H:i:s'),
            $row->produk?->sku,
            $row->produk?->nama,
            $row->produk?->category?->nama,
            $row->jenis,
            $row->jumlah,
            $row->penyebab,
            $row->status,
            $row->petugas?->name,
            $row->tindak_lanjut,
        ];
    }

    public function title(): string
    {
        return 'Audit Barang';
    }
}
