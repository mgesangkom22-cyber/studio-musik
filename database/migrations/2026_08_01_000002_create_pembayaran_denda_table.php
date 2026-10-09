<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pembayaran_denda')) {
            Schema::create('pembayaran_denda', function (Blueprint $table) {
                $table->id('id_pembayaran');
                $table->integer('id_pelanggaran');
                $table->integer('id_admin')->nullable();
                $table->date('tanggal_pembayaran');
                $table->decimal('nominal_pembayaran', 10, 2);
                $table->string('bukti_pembayaran', 255)->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();

                $table->foreign('id_pelanggaran')
                      ->references('id_pelanggaran')
                      ->on('catatan_pelanggaran')
                      ->onDelete('cascade');

                $table->foreign('id_admin')
                      ->references('id_admin')
                      ->on('admin')
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_denda');
    }
};
