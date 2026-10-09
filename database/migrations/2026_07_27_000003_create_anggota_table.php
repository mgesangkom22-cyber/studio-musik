<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('anggota')) {
            Schema::create('anggota', function (Blueprint $table) {
                $table->integer('id_anggota')->autoIncrement();
                $table->integer('id_pendaftaran')->nullable();
                $table->string('nim', 20)->nullable()->unique();
                $table->string('nama', 100)->nullable();
                $table->string('prodi', 100)->nullable();
                $table->text('alamat')->nullable();
                $table->string('id_rfid', 50)->nullable()->unique();
                $table->string('no_hp', 20)->nullable();
                $table->string('email', 100);
                $table->string('foto_ktm', 255)->nullable();
                $table->enum('status_anggota', ['Aktif', 'Ditangguhkan', 'Nonaktif'])->default('Aktif');
                $table->date('tanggal_mulai_sanksi')->nullable();
                $table->date('tanggal_berakhir_sanksi')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('id_pendaftaran', 'fk_anggota_pendaftaran')
                      ->references('id_pendaftaran')
                      ->on('pendaftaran_anggota')
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
