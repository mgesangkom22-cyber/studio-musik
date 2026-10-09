<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class RfidApiController extends Controller
{
    private function validateEsp32Token(Request $request): bool
    {
        $expectedToken = env('ESP32_API_TOKEN', 'UNU_STUDIO_RFID_2026');
        $headerToken = $request->header('X-ESP32-TOKEN') ?? $request->header('X-API-TOKEN') ?? $request->input('api_token');

        if (!$expectedToken) {
            return true;
        }

        return $headerToken === $expectedToken;
    }

    // ESP32 sends UID for RFID Registration mode directly
    public function registerScan(Request $request)
    {
        if (!$this->validateEsp32Token($request)) {
            return response()->json([
                'success' => false,
                'status' => 'unauthorized',
                'beep' => 'fail',
                'message' => 'Unauthorized: Token ESP32 tidak valid.'
            ], 401);
        }

        $uid = strtoupper(trim($request->input('uid_rfid') ?? $request->input('uid') ?? $request->input('id_rfid') ?? ''));

        if (empty($uid)) {
            return response()->json([
                'success' => false,
                'status' => 'empty_uid',
                'beep' => 'fail',
                'message' => 'Parameter uid_rfid wajib diisi.'
            ], 422);
        }

        $now = microtime(true);
        Cache::put('rfid_latest_register_scan', [
            'uid_rfid'  => $uid,
            'scan_time' => $now,
        ], 10);
        Cache::put('rfid_latest_scan', [
            'uid_rfid'  => $uid,
            'scan_time' => $now,
        ], 10);

        // Check if UID is already registered to another member
        $existing = Anggota::where('uid_rfid', $uid)
            ->orWhere('id_rfid', $uid)
            ->first();

        $alreadyRegistered = $existing ? true : false;
        $memberName = $existing ? $existing->nama : null;

        return response()->json([
            'success' => !$alreadyRegistered,
            'status' => $alreadyRegistered ? 'already_registered' : 'available',
            'beep' => $alreadyRegistered ? 'fail' : 'success', // 1 long beep if available, 3 short if already used
            'message' => $alreadyRegistered 
                ? "UID RFID {$uid} sudah terdaftar pada anggota: {$memberName}" 
                : "UID RFID {$uid} berhasil dibaca dan siap didaftarkan.",
            'uid_rfid' => $uid,
            'already_registered' => $alreadyRegistered,
            'anggota' => $existing ? [
                'id_anggota' => $existing->id_anggota,
                'nama' => $existing->nama,
                'nim' => $existing->nim
            ] : null
        ]);
    }

    // ESP32 or Web scans RFID for member lookup in Peminjaman / Pengembalian / Mode Aktif
    public function memberScan(Request $request)
    {
        if (!$this->validateEsp32Token($request)) {
            return response()->json([
                'success' => false,
                'status' => 'unauthorized',
                'beep' => 'fail',
                'message' => 'Unauthorized: Token ESP32 tidak valid.'
            ], 401);
        }

        $uid = strtoupper(trim($request->input('uid_rfid') ?? $request->input('uid') ?? $request->input('id_rfid') ?? ''));

        if (empty($uid)) {
            return response()->json([
                'success' => false,
                'status' => 'empty_uid',
                'beep' => 'fail',
                'message' => 'Parameter uid_rfid wajib diisi.'
            ], 422);
        }

        // Deteksi mode scan aktif dari Cache (set via polling getLatestScanned)
        $activeMode = Cache::get('rfid_active_mode', 'none');

        $anggota = Anggota::where('uid_rfid', $uid)
            ->orWhere('id_rfid', $uid)
            ->first();

        // Update sanksi status hanya untuk anggota yang di-scan
        if ($anggota) {
            Anggota::syncStatus($anggota->id_anggota);
            $anggota->refresh();
        }

        $now = microtime(true);
        $scanPayload = [
            'uid_rfid'   => $uid,
            'scan_time'  => $now,
            'found'      => $anggota ? true : false,
            'id_anggota' => $anggota ? $anggota->id_anggota : null
        ];

        // Cache for frontend auto-fill (Simpan selama 10 detik tanpa dihapus langsung agar PC & HP menerima bersamaan)
        Cache::put('rfid_latest_member_scan', $scanPayload, 10);
        Cache::put('rfid_latest_register_scan', ['uid_rfid' => $uid, 'scan_time' => $now], 10);
        Cache::put('rfid_latest_scan', ['uid_rfid' => $uid, 'scan_time' => $now], 10);

        // ------------------------------------------------------------
        // KONDISI 0: TIDAK ADA HALAMAN/MODAL SCAN AKTIF (Misal di Dashboard)
        // ------------------------------------------------------------
        if ($activeMode === 'none' || empty($activeMode)) {
            return response()->json([
                'success' => false,
                'status' => 'no_active_mode',
                'beep' => 'none', // BUZZER TIDAK BERBUNYI (0 BEEP)
                'message' => 'Tidak ada halaman scan RFID yang aktif.',
                'uid_rfid' => $uid
            ]);
        }

        // ------------------------------------------------------------
        // KONDISI A: MODE REGISTRASI RFID AKTIF (Modal Registrasi Terbuka)
        // ------------------------------------------------------------
        if ($activeMode === 'register') {
            $alreadyRegistered = $anggota ? true : false;
            return response()->json([
                'success' => !$alreadyRegistered,
                'status' => $alreadyRegistered ? 'already_registered' : 'available',
                'beep' => $alreadyRegistered ? 'fail' : 'success', // Available = 1 long beep, Already used = 3 short beeps
                'message' => $alreadyRegistered 
                    ? "UID RFID {$uid} sudah terdaftar pada anggota: {$anggota->nama}" 
                    : "UID RFID {$uid} berhasil dibaca dan siap didaftarkan.",
                'uid_rfid' => $uid,
                'already_registered' => $alreadyRegistered,
                'anggota' => $anggota ? [
                    'id_anggota' => $anggota->id_anggota,
                    'nama' => $anggota->nama,
                    'nim' => $anggota->nim
                ] : null
            ]);
        }

        // ------------------------------------------------------------
        // KONDISI B: MODE PEMINJAMAN / PENGEMBALIAN / MEMBER LOOKUP AKTIF
        // ------------------------------------------------------------
        if (!$anggota) {
            return response()->json([
                'success' => false,
                'status' => 'not_registered',
                'beep' => 'fail', // 3 Bip Pendek (GAGAL)
                'message' => 'Kartu RFID belum terdaftar pada anggota manapun.',
                'uid_rfid' => $uid
            ]);
        }

        $statusLower = strtolower($anggota->status_anggota ?? 'aktif');
        $isAktif = ($statusLower === 'aktif');

        return response()->json([
            'success' => $isAktif,
            'status' => $statusLower,
            'beep' => $isAktif ? 'success' : 'fail', // Aktif = 1 long beep, Ditangguhkan/Nonaktif = 3 short beeps
            'message' => $isAktif ? 'RFID berhasil dikenali.' : 'Status anggota ' . $anggota->status_anggota . ' - Transaksi ditolak.',
            'anggota' => [
                'id_anggota' => $anggota->id_anggota,
                'nama' => $anggota->nama,
                'nim' => $anggota->nim,
                'prodi' => $anggota->prodi,
                'status_anggota' => $anggota->status_anggota,
                'uid_rfid' => $anggota->uid_rfid ?? $anggota->id_rfid,
                'foto_ktm_url' => $anggota->foto_ktm ? asset('uploads/ktm/' . $anggota->foto_ktm) : null
            ]
        ]);
    }

    // Web Frontend Polling endpoint to check for latest scanned RFID (Multi-Client Broadcast)
    // Sekaligus bertindak sebagai heartbeat penentu mode scan aktif (rfid_active_mode)
    public function getLatestScanned(Request $request)
    {
        $type = $request->query('type');

        // Daftarkan mode scan aktif di Cache (berlaku 10 detik heartbeat)
        if (!empty($type)) {
            Cache::put('rfid_active_mode', $type, 10);
        }

        $reg = Cache::get('rfid_latest_register_scan');
        $mem = Cache::get('rfid_latest_member_scan');
        $gen = Cache::get('rfid_latest_scan');

        $activeData = $mem ?? $reg ?? $gen;

        $activeUid = null;
        $scanTime = 0;
        $found = false;
        $idAnggota = null;

        if (is_array($activeData)) {
            $activeUid = $activeData['uid_rfid'] ?? null;
            $scanTime  = $activeData['scan_time'] ?? 0;
            $found     = $activeData['found'] ?? false;
            $idAnggota = $activeData['id_anggota'] ?? null;
        } elseif (is_string($activeData)) {
            $activeUid = $activeData;
        }

        $now = microtime(true);
        // Valid jika pembacaan terjadi dalam 5 detik terakhir
        $isValidScan = (!empty($activeUid) && ($scanTime > 0) && (($now - $scanTime) <= 5.0));

        return response()->json([
            'success'   => $isValidScan,
            'uid_rfid'  => $isValidScan ? $activeUid : null,
            'scan_time' => $scanTime,
            'data'      => [
                'uid_rfid'   => $isValidScan ? $activeUid : null,
                'found'      => $found,
                'id_anggota' => $idAnggota
            ]
        ]);
    }

    // Clear latest scanned RFID buffer
    public function clearLatestScanned(Request $request)
    {
        Cache::forget('rfid_latest_register_scan');
        Cache::forget('rfid_latest_member_scan');
        Cache::forget('rfid_latest_scan');
        Cache::forget('rfid_active_mode');

        return response()->json([
            'success' => true,
            'message' => 'Buffer RFID berhasil dibersihkan.'
        ]);
    }

    // Get active return process state for PC & Mobile multi-client sync
    public function getPengembalianState(Request $request)
    {
        $state = Cache::get('pengembalian_active_state', [
            'id_peminjaman' => null,
            'id_detail'     => null,
            'auto_scan'     => null,
            'updated_at'    => 0,
            'action'        => 'none'
        ]);

        return response()->json([
            'success' => true,
            'data'    => $state
        ]);
    }

    // Update active return process state for PC & Mobile multi-client sync
    public function updatePengembalianState(Request $request)
    {
        $idPeminjaman = $request->input('id_peminjaman');
        $idDetail     = $request->input('id_detail');
        $autoScan     = $request->input('auto_scan');
        $action       = $request->input('action', 'update');

        $state = [
            'id_peminjaman' => $idPeminjaman,
            'id_detail'     => $idDetail,
            'auto_scan'     => $autoScan,
            'updated_at'    => microtime(true),
            'action'        => $action
        ];

        Cache::put('pengembalian_active_state', $state, 60);

        return response()->json([
            'success' => true,
            'data'    => $state
        ]);
    }

    // Get active borrowing process state for PC & Mobile multi-client sync
    public function getPeminjamanState(Request $request)
    {
        $state = Cache::get('peminjaman_active_state', [
            'id_anggota'    => null,
            'selected_alat' => [],
            'updated_at'    => 0,
            'action'        => 'none'
        ]);

        return response()->json([
            'success' => true,
            'data'    => $state
        ]);
    }

    // Update active borrowing process state for PC & Mobile multi-client sync
    public function updatePeminjamanState(Request $request)
    {
        $idAnggota    = $request->input('id_anggota');
        $selectedAlat = $request->input('selected_alat', []);
        $action       = $request->input('action', 'update');

        if ($action === 'clear') {
            Cache::forget('peminjaman_active_state');
            return response()->json([
                'success' => true,
                'message' => 'State peminjaman dibersihkan.'
            ]);
        }

        $state = [
            'id_anggota'    => $idAnggota,
            'selected_alat' => is_array($selectedAlat) ? $selectedAlat : [],
            'updated_at'    => microtime(true),
            'action'        => $action
        ];

        Cache::put('peminjaman_active_state', $state, 300);

        return response()->json([
            'success' => true,
            'data'    => $state
        ]);
    }
}
