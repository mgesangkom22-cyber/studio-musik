<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PendaftaranAnggota;
use App\Models\Anggota;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\PendaftaranDiterimaMail;
use App\Mail\PendaftaranDitolakMail;

class PendaftaranAnggotaController extends Controller
{
    public function index()
    {
        $pendaftaran = PendaftaranAnggota::latest('created_at')->get();

        return view('admin.pendaftaran.index', compact('pendaftaran'));
    }

    public function show ($id)
    {
        $pendaftaran = PendaftaranAnggota::findOrFail($id);

        return view(
            'admin.pendaftaran.show',
            compact('pendaftaran')
        );
    }

    public function terima($id)
    {
        $pendaftaran = PendaftaranAnggota::findOrFail($id);

        // Cek apakah sudah menjadi anggota
        $cekAnggota = Anggota::where('id_pendaftaran', $pendaftaran->id_pendaftaran)
                             ->orWhere('nim', $pendaftaran->nim)
                             ->first();

        if ($cekAnggota) {
            return redirect()
                ->route('admin.pendaftaran.index')
                ->with('error', 'Anggota dengan NIM tersebut sudah pernah diverifikasi.');
        }

        try {
            DB::beginTransaction();

            // Update status pendaftaran
            $pendaftaran->status_verifikasi = 'diterima';
            $pendaftaran->save();

            // Simpan ke tabel anggota
            Anggota::create([
                'id_pendaftaran'            => $pendaftaran->id_pendaftaran,
                'nim'                       => $pendaftaran->nim,
                'nama'                      => $pendaftaran->nama,
                'prodi'                     => $pendaftaran->prodi,
                'alamat'                    => $pendaftaran->alamat,
                'id_rfid'                   => null,
                'no_hp'                     => $pendaftaran->no_hp,
                'email'                     => $pendaftaran->email,
                'foto_ktm'                  => $pendaftaran->foto_ktm,
                'status_anggota'            => 'Aktif',
                'tanggal_mulai_sanksi'      => null,
                'tanggal_berakhir_sanksi'   => null,
            ]);

            DB::commit();

            try {
                Mail::to($pendaftaran->email)
                    ->send(new PendaftaranDiterimaMail($pendaftaran->nama));
                $pendaftaran->status_email = 'terkirim';
                $pendaftaran->save();
            } catch (\Exception $e) {
                $pendaftaran->status_email = 'gagal';
                $pendaftaran->save();
            }

            return redirect()
                ->route('admin.pendaftaran.index')
                ->with('success', 'Pendaftaran anggota berhasil diterima dan akun anggota telah diaktifkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.pendaftaran.index')
                ->with('error', 'Gagal memproses penerimaan anggota: ' . $e->getMessage());
        }
    }
public function tolak(Request $request, $id)
{
    $request->validate([
        'catatan_admin' => 'required'
    ],[
        'catatan_admin.required' => 'Catatan admin wajib diisi.'
    ]);

    $pendaftaran = PendaftaranAnggota::findOrFail($id);

    $pendaftaran->status_verifikasi = 'ditolak';

    $pendaftaran->catatan_admin = $request->catatan_admin;

    $pendaftaran->save();
try {

    Mail::to($pendaftaran->email)
        ->send(
            new PendaftaranDitolakMail(
                $pendaftaran->nama,
                $pendaftaran->catatan_admin
            )
        );

    $pendaftaran->status_email = 'terkirim';
    $pendaftaran->save();

} catch (\Exception $e) {

    $pendaftaran->status_email = 'gagal';
    $pendaftaran->save();

}

    return redirect()
            ->route('admin.pendaftaran.index')
            ->with('success','Pendaftaran berhasil ditolak.');
}

}
