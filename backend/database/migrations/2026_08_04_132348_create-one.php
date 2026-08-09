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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->timestamps();
            $table->boolean('active');
        });
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            $table->date('recorded_on');

            // Optional if you check more than once per day
            // $table->enum('shift', ['morning', 'evening']);

            $table->timestamps();
        });
        Schema::create('omegas', function (Blueprint $table) {
            $table->id();

            // Foreign key linking to barangs table
            $table->foreignId('barang_id')
                ->constrained('barangs')
                ->cascadeOnDelete(); // Or ->restrictOnDelete() depending on your business rule

            // Quantity of goods
            $table->integer('qty');

            // Custom timestamp to track when this Omega log entry was recorded
            $table->timestamp('recorded_at')->useCurrent(); 

            $table->timestamps();
        });
        Schema::create('entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('log_id')
                ->constrained('logs')
                ->cascadeOnDelete();

            $table->foreignId('barang_id')
                ->constrained('barangs')
                ->restrictOnDelete();

            $table->integer('location');

            $table->integer('isi')->default(0);
            $table->integer('tapel')->default(0);
            $table->integer('tinggi')->default(0);
            $table->integer('sisa')->default(0);

            $table->integer('physical_stock')->default(0);

            // snapshot of Omega at the time of checking
            $table->integer('omega_stock')->default(0);

            $table->integer('difference')->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
        Schema::dropIfExists('omegas');
        Schema::dropIfExists('logs');
        Schema::dropIfExists('barangs');
    }
};
