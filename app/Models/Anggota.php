<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Anggota extends Model
{
    protected $table = 'anggota';

    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'id_pendaftaran',
        'nim',
        'nama',
        'prodi',
        'alamat',
        'id_rfid',
        'uid_rfid',
        'email',
        'no_hp',
        'foto_ktm',
        'status_anggota',
        'tanggal_mulai_sanksi',
        'tanggal_berakhir_sanksi',
    ];

    protected $casts = [
        'tanggal_mulai_sanksi' => 'date',
        'tanggal_berakhir_sanksi' => 'date',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranAnggota::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function peminjaman()
    {
        return $this->hasMany(
            Peminjaman::class,
            'id_anggota',
            'id_anggota'
        );
    }

    public function catatanPelanggaran()
    {
        return $this->hasMany(
            CatatanPelanggaran::class,
            'id_anggota',
            'id_anggota'
        );
    }

    /**
     * Synchronize member status based on strict business rules.
     * Rules:
     * 1. Nonaktif: Keterlambatan >= 30 hari.
     * 2. Ditangguhkan: Keterlambatan >= 7 hari ATAU ada denda Belum Lunas ATAU alat rusak/hilang.
     * 3. Aktif: Tidak ada keterlambatan, tidak ada denda Belum Lunas, tidak ada sanksi aktif.
     */
    public static function syncStatus($id_anggota)
    {
        $member = self::find($id_anggota);
        if (!$member) return;

        $today = Carbon::today();

        // Check active borrowings for lateness
        $activeLoans = Peminjaman::where('id_anggota', $id_anggota)
            ->where('status_pinjam', 'dipinjam')
            ->get();

        $maxLateDays = 0;
        foreach ($activeLoans as $loan) {
            $batas = Carbon::parse($loan->batas_kembali);
            if ($today->gt($batas)) {
                $days = (int) $batas->diffInDays($today);
                if ($days > $maxLateDays) {
                    $maxLateDays = $days;
                }
            }
        }

        // Check un-cleared violations with hari_terlambat >= 30
        $has30DaysViolation = CatatanPelanggaran::where('id_anggota', $id_anggota)
            ->where('hari_terlambat', '>=', 30)
            ->exists();

        // 1. Nonaktif Rule (keterlambatan >= 30 hari)
        if ($maxLateDays >= 30 || $has30DaysViolation) {
            $member->update([
                'status_anggota' => 'Nonaktif',
            ]);
            return;
        }

        // Check for unpaid fines
        $hasUnpaidFine = CatatanPelanggaran::where('id_anggota', $id_anggota)
            ->where(function($q) {
                $q->where('status_pembayaran', 'Belum Lunas')
                  ->orWhere('status_pembayaran', 'Belum Dibayar');
            })
            ->exists();

        // 2. Ditangguhkan Rule (keterlambatan >= 7 hari ATAU denda belum lunas)
        if ($maxLateDays >= 7 || $hasUnpaidFine) {
            $member->update([
                'status_anggota' => 'Ditangguhkan',
                'tanggal_mulai_sanksi' => $member->tanggal_mulai_sanksi ?? Carbon::today(),
            ]);
            return;
        }

        // 3. Aktif Rule (Clean of all penalties and unpaid fines)
        $member->update([
            'status_anggota' => 'Aktif',
            'tanggal_mulai_sanksi' => null,
            'tanggal_berakhir_sanksi' => null,
        ]);
    }

    /**
     * Batch update status sanksi for all members
     */
    public static function updateStatusSanksi()
    {
        $allMembers = self::all();
        foreach ($allMembers as $member) {
            self::syncStatus($member->id_anggota);
        }
    }
}