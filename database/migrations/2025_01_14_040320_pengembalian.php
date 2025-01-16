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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->string("id",20)->primary();
            $table->string("peminjaman_detail_id",20);
            $table->string("user_id",20)->nullable();
            $table->datetime("tanggal_kembali")->nullable();
            $table->enum("status_barang",[1,2,3])->nullable();
            $table->enum('status_kembali',["dipinjam","dikembalikan"])->default("dipinjam");

            $table->timestamps();

            $table->foreign('peminjaman_detail_id')->references('id')->on('peminjaman_detail');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
