<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanPelanggaran extends Model
{
    protected $table = 'catatan_pelanggaran';

    protected $primaryKey = 'id_pelanggaran';

    protected $fillable = [
        'id_pengembalian',
        'id_anggota',
        'batas_pengembalian',
        'tanggal_pengembalian',
        'hari_terlambat',
        'denda_keterlambatan',
        'denda_kerusakan',
        'jenis_pelanggaran',
        'keterangan',
        'sanksi',
        'status_pembayaran',
        'tanggal_pembayaran',
    ];

    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function riwayatPembayaran()
    {
        return $this->hasMany(PembayaranDenda::class, 'id_pelanggaran', 'id_pelanggaran')->latest('id_pembayaran');
    }

    public function getTotalDibayarAttribute()
    {
        return (float) $this->riwayatPembayaran->sum('nominal_pembayaran');
    }

    public function getSisaDendaAttribute()
    {
        $totalDenda = (float) ($this->denda_keterlambatan + $this->denda_kerusakan);
        $totalDibayar = $this->total_dibayar;
        return max(0, $totalDenda - $totalDibayar);
    }
}