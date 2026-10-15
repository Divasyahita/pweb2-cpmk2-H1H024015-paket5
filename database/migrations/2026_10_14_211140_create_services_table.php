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
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->cascadeOnDelete();

            $table->date('tanggal_masuk');
            $table->text('keluhan');
            $table->decimal('biaya_jasa', 12, 2)->default(0);
            $table->decimal('biaya_sparepart', 12, 2)->default(0);

            $table->enum('status', [
                'menunggu',
                'dikerjakan',
                'selesai',
                'diambil',
            ])->default('menunggu');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
