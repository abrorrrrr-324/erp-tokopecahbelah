<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PergerakanStok extends Model
{
    protected $table = 'pergerakan_stok';

    public $timestamps = false;

    protected $fillable = [
        'produk_id',
        'jenis',
        'jumlah',
        'referensi_tipe',
        'referensi_id',
        'user_id',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
        ];
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'produk_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
