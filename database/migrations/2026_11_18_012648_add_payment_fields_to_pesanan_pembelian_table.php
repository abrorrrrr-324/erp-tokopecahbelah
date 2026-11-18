<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesanan_pembelian', function (Blueprint $table) {
            $table->string('metode_pembayaran', 20)->nullable()->after('status');
            $table->string('status_pembayaran', 30)->default('belum_dibayar')->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan_pembelian', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'status_pembayaran']);
        });
    }
};
