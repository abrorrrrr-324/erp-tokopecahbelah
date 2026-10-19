<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_penerimaan_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receipt_id')->constrained('penerimaan_barang')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->restrictOnDelete();
            $table->integer('jumlah');
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index('produk_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penerimaan_barang');
    }
};
