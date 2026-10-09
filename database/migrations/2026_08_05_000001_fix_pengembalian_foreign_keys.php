<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pengembalian')) {
            // Ensure id_detail column exists
            Schema::table('pengembalian', function (Blueprint $table) {
                if (!Schema::hasColumn('pengembalian', 'id_detail')) {
                    $table->bigInteger('id_detail')->unsigned()->nullable()->after('id_peminjaman');
                }
            });

            // Add foreign key constraints safely
            Schema::table('pengembalian', function (Blueprint $table) {
                try {
                    $table->foreign('id_peminjaman')
                        ->references('id_peminjaman')
                        ->on('peminjaman')
                        ->onDelete('cascade');
                } catch (\Exception $e) {
                    // Ignore if already exists
                }

                if (Schema::hasTable('detail_peminjaman')) {
                    try {
                        $table->foreign('id_detail')
                            ->references('id_detail')
                            ->on('detail_peminjaman')
                            ->onDelete('set null');
                    } catch (\Exception $e) {
                        // Ignore if already exists
                    }
                }

                if (Schema::hasTable('admin')) {
                    try {
                        $table->foreign('id_admin')
                            ->references('id_admin')
                            ->on('admin')
                            ->onDelete('set null');
                    } catch (\Exception $e) {
                        // Ignore if already exists
                    }
                }
            });
        }

        if (Schema::hasTable('catatan_pelanggaran') && Schema::hasTable('pengembalian')) {
            Schema::table('catatan_pelanggaran', function (Blueprint $table) {
                try {
                    $table->foreign('id_pengembalian')
                        ->references('id_pengembalian')
                        ->on('pengembalian')
                        ->onDelete('cascade');
                } catch (\Exception $e) {
                    // Ignore if already exists
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pengembalian')) {
            Schema::table('pengembalian', function (Blueprint $table) {
                try {
                    $table->dropForeign(['id_peminjaman']);
                } catch (\Exception $e) {}
                try {
                    $table->dropForeign(['id_detail']);
                } catch (\Exception $e) {}
                try {
                    $table->dropForeign(['id_admin']);
                } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('catatan_pelanggaran')) {
            Schema::table('catatan_pelanggaran', function (Blueprint $table) {
                try {
                    $table->dropForeign(['id_pengembalian']);
                } catch (\Exception $e) {}
            });
        }
    }
};
