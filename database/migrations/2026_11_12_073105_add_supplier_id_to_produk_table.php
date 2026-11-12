<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropUnique('produk_sku_unique');
            $table->foreignId('supplier_id')
                ->nullable()
                ->after('id')
                ->constrained('supplier')
                ->nullOnDelete();
            $table->unique(['supplier_id', 'sku'], 'produk_supplier_sku_unique');
        });

        foreach (DB::table('users')->whereIn('role', ['supplier', 'suplier'])->get(['id', 'name', 'email']) as $user) {
            DB::table('supplier')->insertOrIgnore([
                'user_id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropUnique('produk_supplier_sku_unique');
            $table->dropConstrainedForeignId('supplier_id');
            $table->unique('sku');
        });
    }
};
