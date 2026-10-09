<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update status_pembayaran column in catatan_pelanggaran
        if (Schema::hasTable('catatan_pelanggaran')) {
            // First alter column to VARCHAR so MySQL allows new values
            DB::statement("ALTER TABLE `catatan_pelanggaran` MODIFY `status_pembayaran` VARCHAR(50) NOT NULL DEFAULT 'Belum Lunas'");

            // Update existing legacy values to standard business rule values
            DB::table('catatan_pelanggaran')
                ->whereIn('status_pembayaran', ['Belum Dibayar', 'belum_dibayar', 'Belum Lunas'])
                ->update(['status_pembayaran' => 'Belum Lunas']);

            DB::table('catatan_pelanggaran')
                ->whereIn('status_pembayaran', ['Sudah Dibayar', 'sudah_dibayar', 'Lunas'])
                ->update(['status_pembayaran' => 'Lunas']);
        }

        // 2. Update status_alat column in alat
        if (Schema::hasTable('alat')) {
            DB::statement("ALTER TABLE `alat` MODIFY `status_alat` VARCHAR(50) NOT NULL DEFAULT 'Tersedia'");

            DB::table('alat')->whereIn('status_alat', ['tersedia', 'Tersedia'])->update(['status_alat' => 'Tersedia']);
            DB::table('alat')->whereIn('status_alat', ['dipinjam', 'Dipinjam'])->update(['status_alat' => 'Dipinjam']);
            DB::table('alat')->whereIn('status_alat', ['rusak', 'Rusak', 'rusak_ringan', 'Rusak Ringan'])->update(['status_alat' => 'Rusak Ringan']);
            DB::table('alat')->whereIn('status_alat', ['perbaikan', 'maintenance', 'Rusak Berat'])->update(['status_alat' => 'Rusak Berat']);
            DB::table('alat')->whereIn('status_alat', ['hilang', 'Hilang'])->update(['status_alat' => 'Hilang']);
        }

        // 3. Update status_anggota column in anggota
        if (Schema::hasTable('anggota')) {
            DB::statement("ALTER TABLE `anggota` MODIFY `status_anggota` VARCHAR(50) NOT NULL DEFAULT 'Aktif'");

            DB::table('anggota')->whereIn('status_anggota', ['aktif', 'Aktif'])->update(['status_anggota' => 'Aktif']);
            DB::table('anggota')->whereIn('status_anggota', ['ditangguhkan', 'Ditangguhkan'])->update(['status_anggota' => 'Ditangguhkan']);
            DB::table('anggota')->whereIn('status_anggota', ['nonaktif', 'Nonaktif'])->update(['status_anggota' => 'Nonaktif']);
        }
    }

    public function down(): void
    {
        // Revert schema if needed
    }
};
