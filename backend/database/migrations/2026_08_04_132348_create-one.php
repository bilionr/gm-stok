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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('barang')->unique();
            $table->integer('omega')->default(0);
            $table->timestamps();
        });
        Schema::create('stock_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete();
            $table->integer('lokasi');
            $table->integer('isi')->default(1);
            $table->integer('tapel')->default(0);
            $table->integer('tinggi')->default(0);
            $table->integer('sisa')->default(0);
            $table->string('cttn')->nullable()->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_entries');
        Schema::dropIfExists('items');
    }
};
