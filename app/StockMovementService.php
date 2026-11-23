<?php

namespace App;

use App\Models\PergerakanStok;
use App\Models\SupplierProduct;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class StockMovementService
{
    public function record(
        int $productId,
        string $type,
        int $quantity,
        string $referenceType,
        ?int $referenceId,
        int $userId,
        ?string $notes,
    ): PergerakanStok {
        if (! in_array($type, ['masuk', 'keluar', 'rusak', 'hilang'], true) || $quantity < 1) {
            throw new InvalidArgumentException('Jenis dan jumlah mutasi stok tidak valid.');
        }

        $product = SupplierProduct::query()
            ->whereKey($productId)
            ->lockForUpdate()
            ->firstOrFail();

        if ($type !== 'masuk' && $product->stok < $quantity) {
            throw ValidationException::withMessages([
                'jumlah' => 'Jumlah barang keluar melebihi stok yang tersedia.',
            ]);
        }

        if ($type === 'masuk') {
            $product->increment('stok', $quantity);
        } else {
            $product->decrement('stok', $quantity);
        }

        return PergerakanStok::query()->create([
            'produk_id' => $product->id,
            'jenis' => $type,
            'jumlah' => $quantity,
            'referensi_tipe' => $referenceType,
            'referensi_id' => $referenceId,
            'user_id' => $userId,
            'catatan' => $notes,
        ]);
    }
}
