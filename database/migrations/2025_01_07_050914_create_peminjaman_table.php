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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->string('id',20)->primary();
            $table->string('user_id',10);
            $table->datetime('tanggal_peminjaman')->useCurrent();
            $table->unsignedBigInteger('siswa_id');
            $table->datetime('tanggal_pengembalian');
            $table->enum('status_kembali',["dipinjam","dikembalikan"])->default("dipinjam");
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('siswa_id')->references('id')->on('siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
