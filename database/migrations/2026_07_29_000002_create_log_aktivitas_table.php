<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('log_aktivitas')) {
            Schema::create('log_aktivitas', function (Blueprint $table) {
                $table->id('id_log');
                $table->integer('id_admin')->nullable();
                $table->string('nama_admin')->nullable();
                $table->string('aksi');
                $table->string('modul');
                $table->text('deskripsi')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
