<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pengembalian')) {
            Schema::create('pengembalian', function (Blueprint $table) {
                $table->integer('id_pengembalian')->autoIncrement();
                $table->integer('id_peminjaman')->nullable();
                $table->integer('id_admin')->nullable();
                $table->date('tanggal_kembali')->nullable();
                $table->string('kondisi_alat', 100)->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();

                $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman')->onDelete('cascade');
                $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
