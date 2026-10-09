<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranDenda extends Model
{
    protected $table = 'pembayaran_denda';

    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pelanggaran',
        'id_admin',
        'tanggal_pembayaran',
        'nominal_pembayaran',
        'bukti_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'nominal_pembayaran' => 'float',
    ];

    public function catatanPelanggaran()
    {
        return $this->belongsTo(CatatanPelanggaran::class, 'id_pelanggaran', 'id_pelanggaran');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
