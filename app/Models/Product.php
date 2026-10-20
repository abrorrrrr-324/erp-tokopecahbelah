<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'nama',
        'category',
        'category_id',
        'stock',
        'stok',
        'price',
        'harga_jual',
        'buy_price',
        'harga_beli',
        'description',
        'deskripsi',
        'unit',
        'satuan',
        'minimum_stock',
        'stok_minimum',
        'status',
        'photo',
        'created_by',
    ];

    protected $casts = [
        'stock' => 'integer',
        'stok' => 'integer',
        'price' => 'integer',
        'harga_jual' => 'integer',
        'buy_price' => 'integer',
        'harga_beli' => 'integer',
        'minimum_stock' => 'integer',
        'stok_minimum' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
