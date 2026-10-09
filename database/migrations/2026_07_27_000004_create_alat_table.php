<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('alat')) {
            Schema::create('alat', function (Blueprint $table) {
                $table->integer('id_alat')->autoIncrement();
                $table->string('kode_alat', 30)->nullable();
                $table->string('barcode_alat', 100)->nullable();
                $table->string('nama_alat', 100)->nullable();
                $table->string('kategori', 50)->nullable();
                $table->integer('maks_lama_pinjam')->nullable();
                $table->enum('status_alat', ['tersedia', 'dipinjam', 'rusak', 'hilang', 'perbaikan'])->default('tersedia');
                $table->string('foto_alat', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
