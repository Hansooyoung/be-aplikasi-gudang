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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->string('nama',255);
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('jurusan_id');
            $table->string('no_hp');
            $table->timestamps();

            $table->foreign('kelas_id')->references('id')->on('kelas');
            $table->foreign('jurusan_id')->references('id')->on('jurusan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
