<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add header columns to peminjaman if missing
        if (Schema::hasTable('peminjaman')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                if (!Schema::hasColumn('peminjaman', 'kode_transaksi')) {
                    $table->string('kode_transaksi', 50)->nullable()->after('id_peminjaman');
                }
                if (!Schema::hasColumn('peminjaman', 'status_transaksi')) {
                    $table->string('status_transaksi', 50)->default('Aktif')->after('status_pinjam');
                }
            });
        }

        // 2. Create detail_peminjaman table
        if (!Schema::hasTable('detail_peminjaman')) {
            Schema::create('detail_peminjaman', function (Blueprint $table) {
                $table->id('id_detail');
                $table->integer('id_peminjaman');
                $table->integer('id_alat');
                $table->string('status_detail', 50)->default('dipinjam');
                $table->date('tanggal_dikembalikan')->nullable();
                $table->string('kondisi_dikembalikan', 100)->nullable();
                $table->timestamps();

                $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman')->onDelete('cascade');
                $table->foreign('id_alat')->references('id_alat')->on('alat')->onDelete('restrict');
            });
        }

        // 3. Add id_detail to pengembalian if missing
        if (Schema::hasTable('pengembalian')) {
            Schema::table('pengembalian', function (Blueprint $table) {
                if (!Schema::hasColumn('pengembalian', 'id_detail')) {
                    $table->bigInteger('id_detail')->unsigned()->nullable()->after('id_peminjaman');
                }
            });
        }

        // 4. Migrate existing single-item peminjaman records into detail_peminjaman
        if (Schema::hasTable('peminjaman') && Schema::hasTable('detail_peminjaman')) {
            $existingLoans = DB::table('peminjaman')->get();
            foreach ($existingLoans as $loan) {
                // Generate transaction code if null
                $kodeTrx = $loan->kode_transaksi ?? ('TRX-' . date('Ymd', strtotime($loan->tanggal_pinjam ?? now())) . sprintf('%04d', $loan->id_peminjaman));
                $stTrx = strtolower($loan->status_pinjam ?? '') === 'dikembalikan' ? 'Selesai' : 'Aktif';

                DB::table('peminjaman')->where('id_peminjaman', $loan->id_peminjaman)->update([
                    'kode_transaksi' => $kodeTrx,
                    'status_transaksi' => $stTrx,
                ]);

                if ($loan->id_alat) {
                    $detailExists = DB::table('detail_peminjaman')
                        ->where('id_peminjaman', $loan->id_peminjaman)
                        ->where('id_alat', $loan->id_alat)
                        ->first();

                    if (!$detailExists) {
                        $stDetail = strtolower($loan->status_pinjam ?? '') === 'dikembalikan' ? 'dikembalikan' : 'dipinjam';
                        $detailId = DB::table('detail_peminjaman')->insertGetId([
                            'id_peminjaman' => $loan->id_peminjaman,
                            'id_alat' => $loan->id_alat,
                            'status_detail' => $stDetail,
                            'created_at' => $loan->created_at ?? now(),
                            'updated_at' => $loan->updated_at ?? now(),
                        ]);

                        // Link existing pengembalian to id_detail
                        DB::table('pengembalian')
                            ->where('id_peminjaman', $loan->id_peminjaman)
                            ->whereNull('id_detail')
                            ->update(['id_detail' => $detailId]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
