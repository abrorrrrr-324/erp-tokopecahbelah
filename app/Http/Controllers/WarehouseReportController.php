<?php

namespace App\Http\Controllers;

use App\Exports\WarehouseReportExport;
use App\Models\ProductCategory;
use App\Models\SupplierProduct;
use App\WarehouseReportQuery;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class WarehouseReportController extends Controller
{
    public function __construct(private WarehouseReportQuery $reportQuery) {}

    public function index(Request $request): View
    {
        $filters = $this->validateFilters($request);
        $stocks = $this->reportQuery->stocks($filters)
            ->paginate(20, ['*'], 'stok_page')
            ->withQueryString();
        $movements = $this->reportQuery->movements($filters)
            ->paginate(20, ['*'], 'mutasi_page')
            ->withQueryString();
        $audits = $this->reportQuery->audits($filters)
            ->paginate(20, ['*'], 'audit_page')
            ->withQueryString();
        $categories = ProductCategory::query()->orderBy('nama')->get(['id', 'nama']);
        $products = SupplierProduct::query()->orderBy('nama')->get(['id', 'nama']);

        return view('kepalagudang.laporan.index', compact(
            'filters',
            'stocks',
            'movements',
            'audits',
            'categories',
            'products',
        ));
    }

    public function print(Request $request): View
    {
        $filters = $this->validateFilters($request);

        return view('kepalagudang.laporan.document', $this->reportData($filters) + [
            'showPrintButton' => true,
        ]);
    }

    public function pdf(Request $request): Response
    {
        $filters = $this->validateFilters($request);

        return Pdf::loadView('kepalagudang.laporan.document', $this->reportData($filters))
            ->setPaper('a4', 'landscape')
            ->download('laporan-gudang-'.now()->format('Ymd-His').'.pdf');
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $filters = $this->validateFilters($request);

        return Excel::download(
            new WarehouseReportExport($this->reportQuery, $filters),
            'laporan-gudang-'.now()->format('Ymd-His').'.xlsx',
        );
    }

    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'category_id' => ['nullable', 'integer', 'exists:kategori_produk,id'],
            'produk_id' => ['nullable', 'integer', 'exists:produk,id'],
            'jenis' => ['nullable', 'in:masuk,keluar,rusak,hilang'],
        ]);
    }

    private function reportData(array $filters): array
    {
        return [
            'filters' => $filters,
            'stocks' => $this->reportQuery->stocks($filters)->get(),
            'movements' => $this->reportQuery->movements($filters)->get(),
            'audits' => $this->reportQuery->audits($filters)->get(),
            'generatedAt' => now(),
        ];
    }
}
