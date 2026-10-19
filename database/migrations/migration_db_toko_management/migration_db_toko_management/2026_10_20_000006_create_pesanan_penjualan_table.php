<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesanan_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('pelanggan')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tanggal_pesanan')->useCurrent();
            $table->decimal('total', 15, 2)->default(0);
            $table->string('status', 30)->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->index('tanggal_pesanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_penjualan');
    }
};
