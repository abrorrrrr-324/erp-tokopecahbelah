<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAuditKerusakan extends Model
{
    protected $table = 'log_audit_kerusakan';

    protected $fillable = [
        'produk_id',
        'jumlah_rusak',
        'tanggal_rusak',
        'keterangan_kerusakan',
        'foto_bukti',
        'user_id',
    ];

    protected $casts = [
        'tanggal_rusak' => 'date',
        'jumlah_rusak' => 'integer',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
