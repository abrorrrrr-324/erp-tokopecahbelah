<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenerimaanBarang extends Model
{
    protected $table = 'penerimaan_barang';

    protected $fillable = [
        'user_id',
        'purchase_order_id',
        'tanggal_penerimaan',
        'status',
        'catatan',
        'foto_bukti',
        'tindak_lanjut',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penerimaan' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(
            PurchaseOrder::class,
            'purchase_order_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(
            DetailPenerimaanBarang::class,
            'goods_receipt_id'
        );
    }
}
