<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    public function index()
    {
        Anggota::updateStatusSanksi();
        $anggota = Anggota::orderBy('created_at', 'desc')->orderBy('id_anggota', 'desc')->get();

        return view('admin.anggota.index', compact('anggota'));
    }

    public function show($id)
    {
        Anggota::updateStatusSanksi();
        $anggota = Anggota::findOrFail($id);

        return view(
            'admin.anggota.show',
            compact('anggota')
        );
    }

    public function edit($id)
    {
        Anggota::updateStatusSanksi();
        $anggota = Anggota::findOrFail($id);

        return view(
            'admin.anggota.edit',
            compact('anggota')
        );
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $rfidVal = strtoupper(trim($request->id_rfid ?? $request->uid_rfid ?? ''));

        $request->validate([
            'nama' => 'required',
            'prodi' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('anggota', 'email')->ignore($id, 'id_anggota'),
            ],
            'id_rfid' => 'nullable|string|max:50|unique:anggota,id_rfid,'.$id.',id_anggota',
            'uid_rfid' => 'nullable|string|max:50|unique:anggota,uid_rfid,'.$id.',id_anggota',
            'status_anggota' => 'required',
            'tanggal_mulai_sanksi' => 'nullable|date',
            'tanggal_berakhir_sanksi' => 'nullable|date',
        ], [
            'email.unique' => 'Email tersebut sudah terdaftar pada anggota lain.',
            'id_rfid.unique' => 'Kartu RFID tersebut sudah terdaftar pada anggota lain.',
            'uid_rfid.unique' => 'Kartu RFID tersebut sudah terdaftar pada anggota lain.',
        ]);

        $anggota->update([
            'nama' => $request->nama,
            'prodi' => $request->prodi,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'id_rfid' => $rfidVal ?: null,
            'uid_rfid' => $rfidVal ?: null,
            'status_anggota' => $request->status_anggota,
            'tanggal_mulai_sanksi' => $request->tanggal_mulai_sanksi,
            'tanggal_berakhir_sanksi' => $request->tanggal_berakhir_sanksi,
        ]);

        // Enforce business rules synchronization
        Anggota::syncStatus($anggota->id_anggota);

        return redirect()
            ->route('admin.anggota.show', $anggota->id_anggota)
            ->with('success', 'Data anggota berhasil diperbarui dan status disinkronkan dengan aturan bisnis.');
    }

    public function updateRfid(Request $request, $id)
    {
        $request->validate([
            'uid_rfid' => 'required|string|max:50',
        ], [
            'uid_rfid.required' => 'UID RFID wajib diisi atau discan via ESP32.'
        ]);

        $uid = strtoupper(trim($request->uid_rfid));

        $duplicate = Anggota::where(function($q) use ($uid) {
                $q->where('uid_rfid', $uid)->orWhere('id_rfid', $uid);
            })
            ->where('id_anggota', '!=', $id)
            ->first();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu RFID sudah terdaftar pada anggota lain.'
            ], 422);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $anggota = Anggota::findOrFail($id);
            $oldUid = $anggota->uid_rfid ?? $anggota->id_rfid;

            $anggota->update([
                'id_rfid' => $uid,
                'uid_rfid' => $uid,
            ]);

            \Illuminate\Support\Facades\Cache::forget('rfid_latest_register_scan');
            \Illuminate\Support\Facades\Cache::forget('rfid_latest_member_scan');
            \Illuminate\Support\Facades\Cache::forget('rfid_latest_scan');

            $action = $oldUid ? 'Pergantian RFID' : 'Registrasi RFID';
            $desc = $oldUid 
                ? "Pergantian kartu RFID untuk anggota {$anggota->nama} (NIM: {$anggota->nim}) dari UID {$oldUid} menjadi UID {$uid}."
                : "Registrasi kartu RFID UID {$uid} untuk anggota {$anggota->nama} (NIM: {$anggota->nim}).";

            \App\Models\ActivityLog::record($action, 'Anggota', $desc);

            \Illuminate\Support\Facades\DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kartu RFID ' . $uid . ' berhasil didaftarkan untuk ' . $anggota->nama . '.',
                    'data' => [
                        'id_anggota' => $anggota->id_anggota,
                        'nama' => $anggota->nama,
                        'uid_rfid' => $uid
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Kartu RFID ' . $uid . ' berhasil didaftarkan untuk ' . $anggota->nama . '.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan RFID: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal menyimpan RFID: ' . $e->getMessage());
        }
    }

    public function findByRfid($uid)
    {
        Anggota::updateStatusSanksi();

        $uidClean = strtoupper(trim($uid));

        $anggota = Anggota::where('uid_rfid', $uidClean)
            ->orWhere('id_rfid', $uidClean)
            ->first();

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu RFID (' . $uidClean . ') belum terdaftar pada anggota manapun.'
            ], 404);
        }

        $activeTransaction = \App\Models\Peminjaman::with(['details.alat', 'alat'])
            ->where('id_anggota', $anggota->id_anggota)
            ->where(function($q) {
                $q->where('status_transaksi', 'Aktif')
                  ->orWhere('status_pinjam', 'dipinjam');
            })
            ->latest('id_peminjaman')
            ->first();

        $activeLoans = \App\Models\Peminjaman::with(['details.alat', 'alat'])
            ->where('id_anggota', $anggota->id_anggota)
            ->where(function($q) {
                $q->where('status_transaksi', 'Aktif')
                  ->orWhere('status_pinjam', 'dipinjam');
            })
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Anggota ditemukan.',
            'has_active_transaction' => $activeTransaction ? true : false,
            'active_transaction' => $activeTransaction,
            'anggota' => [
                'id_anggota' => $anggota->id_anggota,
                'nama' => $anggota->nama,
                'nim' => $anggota->nim,
                'prodi' => $anggota->prodi,
                'no_hp' => $anggota->no_hp,
                'email' => $anggota->email,
                'status_anggota' => $anggota->status_anggota,
                'uid_rfid' => $anggota->uid_rfid ?? $anggota->id_rfid,
                'foto_ktm_url' => $anggota->foto_ktm ? asset('uploads/ktm/' . $anggota->foto_ktm) : null,
                'active_loans' => $activeLoans
            ]
        ]);
    }

    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);

        // 1. Cek peminjaman aktif / alat belum dikembalikan
        $activeLoan = \App\Models\Peminjaman::where('id_anggota', $id)
            ->where(function($q) {
                $q->where('status_pinjam', 'dipinjam')
                  ->orWhere('status_transaksi', 'Aktif');
            })
            ->exists();

        if ($activeLoan) {
            return redirect()
                ->route('admin.anggota.index')
                ->with('error', 'Anggota tidak dapat dihapus karena masih memiliki peminjaman aktif. Pastikan seluruh alat musik dikembalikan terlebih dahulu.');
        }

        // 2. Cek denda belum lunas
        $unpaidFine = \App\Models\CatatanPelanggaran::where('id_anggota', $id)
            ->where(function($q) {
                $q->where('status_pembayaran', 'Belum Lunas')
                  ->orWhere('status_pembayaran', 'Belum Dibayar');
            })
            ->exists();

        if ($unpaidFine) {
            return redirect()
                ->route('admin.anggota.index')
                ->with('error', 'Anggota tidak dapat dihapus karena masih memiliki denda yang belum lunas.');
        }

        // 3. Cek riwayat transaksi historis yang sudah selesai/lunas
        $hasHistory = $anggota->peminjaman()->exists() || $anggota->catatanPelanggaran()->exists();

        if ($hasHistory) {
            return redirect()
                ->route('admin.anggota.index')
                ->with('error', 'Data anggota tidak dapat dihapus dari database karena memiliki riwayat peminjaman/pelanggaran historis. Data riwayat tetap dipertahankan untuk integritas laporan.');
        }

        // 4. Jika tidak ada tanggungan dan 0 riwayat transaksi: Hard delete dari database
        if ($anggota->foto_ktm && file_exists(public_path('uploads/ktm/' . $anggota->foto_ktm))) {
            @unlink(public_path('uploads/ktm/' . $anggota->foto_ktm));
        }

        $anggota->delete();

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', 'Data anggota berhasil dihapus secara permanen dari database.');
    }

    public function checkRfidAvailability(Request $request)
    {
        $uid = strtoupper(trim($request->query('uid') ?? $request->query('uid_rfid') ?? ''));
        $id = $request->query('id_anggota');

        if (empty($uid)) {
            return response()->json([
                'available' => false,
                'message' => 'Silakan scan atau masukkan UID RFID.'
            ]);
        }

        $duplicate = Anggota::where(function($q) use ($uid) {
                $q->where('uid_rfid', $uid)->orWhere('id_rfid', $uid);
            })
            ->when($id, function($q) use ($id) {
                $q->where('id_anggota', '!=', $id);
            })
            ->first();

        if ($duplicate) {
            return response()->json([
                'available' => false,
                'message' => '✕ UID RFID sudah terdaftar pada anggota ' . $duplicate->nama . '.',
                'owner_nama' => $duplicate->nama,
                'owner_nim' => $duplicate->nim
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => '✓ UID RFID tersedia. Kartu RFID siap digunakan.'
        ]);
    }
}