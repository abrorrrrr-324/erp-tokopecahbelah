<?php

namespace App\Exports;

use App\WarehouseReportQuery;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class WarehouseReportExport implements Export, WithMultipleSheets
{
    public function __construct(
        private WarehouseReportQuery $reportQuery,
        private array $filters,
    ) {}

    public function sheets(): array
    {
        return [
            new WarehouseStockExport($this->reportQuery, $this->filters),
            new WarehouseMovementExport($this->reportQuery, $this->filters),
            new WarehouseAuditExport($this->reportQuery, $this->filters),
        ];
    }
}
