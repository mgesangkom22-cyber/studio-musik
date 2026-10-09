<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlatMusik extends Model
{
    protected $table = 'alat';

    protected $primaryKey = 'id_alat';

    protected $fillable = [
        'kode_alat',
        'barcode_alat',
        'nama_alat',
        'kategori',
        'maks_lama_pinjam',
        'status_alat',
        'foto_alat',
    ];

    public function peminjaman()
    {
        return $this->hasMany(
            Peminjaman::class,
            'id_alat',
            'id_alat'
        );
    }

    public function scopeTersedia($query)
    {
        return $query->whereIn('status_alat', ['Tersedia', 'tersedia']);
    }
}