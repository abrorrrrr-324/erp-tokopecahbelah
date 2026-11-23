<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenerimaanBarang extends Model
{
    protected $table = 'detail_penerimaan_barang';

    public $timestamps = false;

    protected $fillable = [
        'goods_receipt_id',
        'produk_id',
        'jumlah',
        'jumlah_diterima',
        'jumlah_rusak',
        'jumlah_hilang',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'jumlah_diterima' => 'integer',
            'jumlah_rusak' => 'integer',
            'jumlah_hilang' => 'integer',
        ];
    }

    public function penerimaanBarang(): BelongsTo
    {
        return $this->belongsTo(
            PenerimaanBarang::class,
            'goods_receipt_id'
        );
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(
            SupplierProduct::class,
            'produk_id'
        );
    }
}
