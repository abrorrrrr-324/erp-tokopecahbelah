<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAudit extends Model
{
    protected $fillable = [
        'produk_id',
        'user_id',
        'reviewed_by',
        'jenis',
        'jumlah',
        'penyebab',
        'catatan',
        'foto_bukti',
        'status',
        'tindak_lanjut',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(SupplierProduct::class, 'produk_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
