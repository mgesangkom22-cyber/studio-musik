<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('catatan_pelanggaran')) {
            Schema::create('catatan_pelanggaran', function (Blueprint $table) {
                $table->integer('id_pelanggaran')->autoIncrement();
                $table->integer('id_pengembalian');
                $table->integer('id_anggota');
                $table->date('batas_pengembalian');
                $table->date('tanggal_pengembalian');
                $table->integer('hari_terlambat')->default(0);
                $table->decimal('denda_keterlambatan', 10, 2)->default(0.00);
                $table->decimal('denda_kerusakan', 10, 2)->default(0.00);
                $table->string('jenis_pelanggaran', 100);
                $table->string('keterangan', 255)->nullable();
                $table->string('sanksi', 100)->nullable();
                $table->enum('status_pembayaran', ['Belum Dibayar', 'Sudah Dibayar'])->default('Belum Dibayar');
                $table->date('tanggal_pembayaran')->nullable();
                $table->timestamps();

                $table->foreign('id_pengembalian')->references('id_pengembalian')->on('pengembalian')->onDelete('cascade');
                $table->foreign('id_anggota')->references('id_anggota')->on('anggota')->onDelete('restrict');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_pelanggaran');
    }
};
