<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pendaftaran_anggota')) {
            Schema::create('pendaftaran_anggota', function (Blueprint $table) {
                $table->integer('id_pendaftaran')->autoIncrement();
                $table->string('nama', 100);
                $table->string('nim', 20)->unique();
                $table->string('prodi', 100);
                $table->text('alamat');
                $table->string('no_hp', 20);
                $table->string('email', 100);
                $table->string('foto_ktm', 255);
                $table->enum('status_verifikasi', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
                $table->text('catatan_admin')->nullable();
                $table->enum('status_email', ['pending', 'terkirim', 'gagal'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_anggota');
    }
};
