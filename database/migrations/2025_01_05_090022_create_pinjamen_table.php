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
        Schema::create('pinjamen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggotas')->cascadeOnDelete();
            $table->foreignId('jenis_pinjaman_id')->constrained('jenis_pinjamen')->cascadeOnDelete();
            $table->foreignId('tujuan_pinjaman_id')->constrained('tujuan_pinjamen')->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->integer('tenor');
            $table->integer('bunga');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjamen');
    }
};
