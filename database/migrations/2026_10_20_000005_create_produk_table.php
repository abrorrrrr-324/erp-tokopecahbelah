<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 50)->unique();
            $table->string('nama', 150);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->string('satuan', 30)->default('pcs');
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->string('status', 20)->default('aktif');
            $table->foreignId('category_id')->nullable()->constrained('kategori_produk')->nullOnDelete();
            $table->timestamps();
            $table->index('nama');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
