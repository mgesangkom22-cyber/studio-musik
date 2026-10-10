<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'kode_transaksi',
        'id_anggota',
        'id_alat',
        'id_admin',
        'tanggal_pinjam',
        'batas_kembali',
        'status_pinjam',
        'status_transaksi',
    ];

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function anggota()
    {
        return $this->belongsTo(
            Anggota::class,
            'id_anggota',
            'id_anggota'
        );
    }

    public function alat()
    {
        return $this->belongsTo(
            AlatMusik::class,
            'id_alat',
            'id_alat'
        );
    }

    public function pengembalian()
    {
        return $this->hasOne(
            Pengembalian::class,
            'id_peminjaman',
            'id_peminjaman'
        );
    }

    public function admin()
    {
        return $this->belongsTo(
            Admin::class,
            'id_admin',
            'id_admin'
        );
    }
}