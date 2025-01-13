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
        Schema::create('peminjaman_detail', function (Blueprint $table) {
            $table->string("id",20)->primary();
            $table->string("peminjaman_id",20);
            $table->string("kode_barang",20);
            $table->datetime("tanggal_peminjaman");
            $table->timestamps();

            $table->foreign('peminjaman_id')->references('id')->on('peminjaman');
            $table->foreign('kode_barang')->references('kode_barang')->on('barang_inventaris');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barang');
    }
};
