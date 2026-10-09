<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranAnggota extends Model
{
    protected $table = 'pendaftaran_anggota';

    protected $primaryKey = 'id_pendaftaran';

    protected $fillable = [
        'nama',
        'nim',
        'prodi',
        'alamat',
        'no_hp',
        'email',
        'foto_ktm',
        'status_verifikasi',
        'status_email',
        'catatan_admin'
    ];

}
