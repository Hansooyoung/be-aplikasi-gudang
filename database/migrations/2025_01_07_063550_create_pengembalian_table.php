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
            $table->string("peminjaman_id",20);
            $table->string("user_id",20);
            $table->datetime("tanggal_peminjaman");
            $table->enum('status',[0,1])->default(0);//0=dipinjam,1=dikembalikan
            $table->timestamps();

            $table->foreign('peminjaman_id')->references('id')->on('peminjaman');
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
