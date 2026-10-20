<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('pesanan_penjualan')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->integer('jumlah');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->index('produk_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
