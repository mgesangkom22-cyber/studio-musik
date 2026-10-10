<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    protected $primaryKey = 'id_pengembalian';

    protected $fillable = [
        'id_peminjaman',
        'id_detail',
        'id_admin',
        'tanggal_kembali',
        'kondisi_alat',
        'keterangan',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }

    public function detailPeminjaman()
    {
        return $this->belongsTo(DetailPeminjaman::class, 'id_detail', 'id_detail');
    }

    public function detail()
    {
        return $this->belongsTo(DetailPeminjaman::class, 'id_detail', 'id_detail');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }

    public function catatanPelanggaran()
    {
        return $this->hasOne(CatatanPelanggaran::class, 'id_pengembalian', 'id_pengembalian');
    }
}