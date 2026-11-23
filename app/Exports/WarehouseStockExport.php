<?php

namespace App\Exports;

use App\Models\SupplierProduct;
use App\WarehouseReportQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class WarehouseStockExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private WarehouseReportQuery $reportQuery,
        private array $filters,
    ) {}

    public function query(): Builder
    {
        return $this->reportQuery->stocks($this->filters);
    }

    public function headings(): array
    {
        return ['SKU', 'Produk', 'Kategori', 'Stok Saat Ini', 'Stok Minimum', 'Satuan'];
    }

    public function map(mixed $row): array
    {
        if (! $row instanceof SupplierProduct) {
            return [];
        }

        return [
            $row->sku,
            $row->nama,
            $row->category?->nama,
            $row->stok,
            $row->stok_minimum,
            $row->satuan,
        ];
    }

    public function title(): string
    {
        return 'Stok Saat Ini';
    }
}
