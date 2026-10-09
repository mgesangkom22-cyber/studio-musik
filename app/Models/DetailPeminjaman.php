<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_peminjaman',
        'id_alat',
        'status_detail',
        'tanggal_dikembalikan',
        'kondisi_dikembalikan',
    ];

    protected $casts = [
        'tanggal_dikembalikan' => 'date',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function alat()
    {
        return $this->belongsTo(AlatMusik::class, 'id_alat', 'id_alat');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'id_detail', 'id_detail');
    }
}
