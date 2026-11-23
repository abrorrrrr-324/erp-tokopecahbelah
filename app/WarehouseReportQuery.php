<?php

namespace App;

use App\Models\PergerakanStok;
use App\Models\StockAudit;
use App\Models\SupplierProduct;
use Illuminate\Database\Eloquent\Builder;

class WarehouseReportQuery
{
    public function stocks(array $filters): Builder
    {
        return SupplierProduct::query()
            ->with('category')
            ->when($filters['produk_id'] ?? null, fn (Builder $query, $productId) => $query->whereKey($productId))
            ->when($filters['category_id'] ?? null, fn (Builder $query, $categoryId) => $query->where('category_id', $categoryId))
            ->orderBy('nama');
    }

    public function movements(array $filters): Builder
    {
        return PergerakanStok::query()
            ->with(['produk.category', 'user'])
            ->when($filters['produk_id'] ?? null, fn (Builder $query, $productId) => $query->where('produk_id', $productId))
            ->when($filters['category_id'] ?? null, fn (Builder $query, $categoryId) => $query->whereHas(
                'produk',
                fn (Builder $productQuery) => $productQuery->where('category_id', $categoryId),
            ))
            ->when($filters['jenis'] ?? null, fn (Builder $query, $type) => $query->where('jenis', $type))
            ->when($filters['tanggal_mulai'] ?? null, fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date))
            ->latest('created_at')
            ->latest('id');
    }

    public function audits(array $filters): Builder
    {
        $query = StockAudit::query()
            ->with(['produk.category', 'petugas', 'reviewedBy'])
            ->when($filters['produk_id'] ?? null, fn (Builder $builder, $productId) => $builder->where('produk_id', $productId))
            ->when($filters['category_id'] ?? null, fn (Builder $builder, $categoryId) => $builder->whereHas(
                'produk',
                fn (Builder $productQuery) => $productQuery->where('category_id', $categoryId),
            ))
            ->when($filters['tanggal_mulai'] ?? null, fn (Builder $builder, $date) => $builder->whereDate('created_at', '>=', $date))
            ->when($filters['tanggal_selesai'] ?? null, fn (Builder $builder, $date) => $builder->whereDate('created_at', '<=', $date));

        if (isset($filters['jenis'])) {
            if (in_array($filters['jenis'], ['rusak', 'hilang'], true)) {
                $query->where('jenis', $filters['jenis']);
            } else {
                $query->whereIn('jenis', []);
            }
        }

        return $query->latest('created_at')->latest('id');
    }
}
