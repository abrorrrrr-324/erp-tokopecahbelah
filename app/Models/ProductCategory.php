<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $table = 'kategori_produk';

    protected $fillable = [
        'nama',
        'deskripsi',
        'status',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(SupplierProduct::class, 'category_id');
    }
}
