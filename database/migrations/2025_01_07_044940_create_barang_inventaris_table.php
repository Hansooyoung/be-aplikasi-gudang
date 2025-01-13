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
        Schema::create('barang_inventaris', function (Blueprint $table) {
            $table->string('kode_barang',12)->primary();
            $table->string('jenis_barang_kode',12);
            $table->string('user_id',10);
            $table->string('nama_barang',50);
            $table->date('tanggal_terima');
            $table->datetime('tanggal_entry')->useCurrent();
            $table->enum('status_barang',[1,2,3]);//1 =baik,2=normal,3=rusak
            $table->enum('status_tersedia',['tersedia','tidak_tersedia'])->default('tersedia');
            $table->string('sumber_id');
            $table->timestamps();

            $table->foreign('jenis_barang_kode')->references('jenis_barang_kode')->on('jenis_barang');
            $table->foreign('user_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_inventaris');
    }
};
