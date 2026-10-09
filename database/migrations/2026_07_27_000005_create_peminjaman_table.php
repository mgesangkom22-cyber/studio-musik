<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('peminjaman')) {
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->integer('id_peminjaman')->autoIncrement();
                $table->integer('id_anggota')->nullable();
                $table->integer('id_alat')->nullable();
                $table->integer('id_admin')->nullable();
                $table->date('tanggal_pinjam')->nullable();
                $table->date('batas_kembali')->nullable();
                $table->enum('status_pinjam', ['dipinjam', 'dikembalikan'])->default('dipinjam');
                $table->timestamps();

                $table->foreign('id_anggota')->references('id_anggota')->on('anggota')->onDelete('restrict');
                $table->foreign('id_alat')->references('id_alat')->on('alat')->onDelete('restrict');
                $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
