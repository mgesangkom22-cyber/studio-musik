<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_admin',
        'nama_admin',
        'aksi',
        'modul',
        'deskripsi',
        'ip_address',
    ];

    /**
     * Record an administrative activity log entry.
     */
    public static function record(string $aksi, string $modul, ?string $deskripsi = null): self
    {
        $idAdmin = session('id_admin');
        $namaAdmin = session('nama') ?? 'System / Admin';

        return self::create([
            'id_admin'   => $idAdmin,
            'nama_admin' => $namaAdmin,
            'aksi'       => $aksi,
            'modul'      => $modul,
            'deskripsi'  => $deskripsi,
            'ip_address' => request()->ip() ?? '127.0.0.1',
        ]);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }
}
