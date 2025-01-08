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
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nik',length: 16)->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin',['L','P']);
            $table->string('provinsi_id',length:50);
            $table->string('kabkota_id',length:50);
            $table->string('kecamatan_id',length:50);
            $table->string('desa_id',length:50);
            $table->string('alamat');
            $table->string('rt',length: 3);
            $table->string('rw',length: 3);
            $table->string('no_hp');
            $table->string('email');
            $table->date('tanggal_masuk');
            $table->boolean('is_active');
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
