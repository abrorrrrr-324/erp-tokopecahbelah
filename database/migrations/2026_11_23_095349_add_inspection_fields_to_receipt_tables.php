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
        Schema::table('penerimaan_barang', function (Blueprint $table) {
            $table->string('foto_bukti')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->unique('purchase_order_id');
        });

        Schema::table('detail_penerimaan_barang', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_diterima')->default(0);
            $table->unsignedInteger('jumlah_rusak')->default(0);
            $table->unsignedInteger('jumlah_hilang')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_penerimaan_barang', function (Blueprint $table) {
            $table->dropColumn([
                'jumlah_diterima',
                'jumlah_rusak',
                'jumlah_hilang',
            ]);
        });

        Schema::table('penerimaan_barang', function (Blueprint $table) {
            $table->dropUnique(['purchase_order_id']);
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn([
                'foto_bukti',
                'tindak_lanjut',
                'reviewed_at',
            ]);
        });
    }
};
