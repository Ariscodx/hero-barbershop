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
        Schema::create('booking', function (Blueprint $table) {
            $table->id('id_booking');
            $table->foreignId('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->cascadeOnDelete();
            $table->foreignId('id_kuota')->references('id_kuota')->on('kuota_booking')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('no_tlp', 13);
            $table->text('alamat');
            $table->date('tgl_booking');
            $table->string('hari', 10);
            $table->time('jam_booking');
            $table->enum('status', ['terkonfirmasi', 'berlangsung', 'selesai', 'dibatalkan'])->default('terkonfirmasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
