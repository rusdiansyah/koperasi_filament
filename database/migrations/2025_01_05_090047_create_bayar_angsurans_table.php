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
        Schema::create('bayar_angsurans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('angsuran_id')->constrained('angsurans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bayar_angsurans');
    }
};
