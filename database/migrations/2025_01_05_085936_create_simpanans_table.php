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
        Schema::create('simpanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggotas')->cascadeOnDelete();
            $table->foreignId('jenis_simpanan_id')->constrained('jenis_simpanans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->enum('jenis_transaksi',['setoran','penarikan']);
            $table->string('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanans');
    }
};
