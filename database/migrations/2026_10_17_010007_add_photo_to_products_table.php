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
        if (! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('sku')->nullable();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('category');
                $table->integer('buy_price')->default(0);
                $table->integer('price')->default(0);
                $table->string('unit')->default('pcs');
                $table->integer('stock')->default(0);
                $table->integer('minimum_stock')->default(0);
                $table->string('status')->default('Aktif');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasColumn('products', 'photo')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('photo')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'photo')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('photo');
            });
        }
    }
};
