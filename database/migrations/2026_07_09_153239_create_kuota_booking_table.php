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
        Schema::create('kuota_booking', function (Blueprint $table) {
            $table->id('id_kuota');
            $table->foreignId('id_jadwal')->references('id_Jadwal_operasional')->on('jadwal')->cascadeOnDelete();
            $table->date('tgl');
            $table->string('hari', 10);
            $table->time('jam');
            $table->enum('status_kuota', ['aktif', 'nonaktif'])->default('aktif');
            $table->enum('status_booking', ['tersedia', 'terbooking'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuota_booking');
    }
};
