
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_audit_kerusakan', function (Blueprint $table) {
            $table->id();

            // Relasi ke produk
            $table->foreignId('produk_id')
                ->constrained('produk')
                ->restrictOnDelete();

            // Data kerusakan
            $table->unsignedInteger('jumlah_rusak');
            $table->date('tanggal_rusak');
            $table->text('keterangan_kerusakan');

            // Foto bukti kerusakan
            $table->string('foto_bukti')->nullable();

            // User yang mencatat audit
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_audit_kerusakan');
    }
};
