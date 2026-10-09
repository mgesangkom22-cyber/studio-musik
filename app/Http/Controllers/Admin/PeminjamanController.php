<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\AlatMusik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['anggota', 'details.alat', 'alat'])->latest()->get();

        return view(
            'admin.peminjaman.index',
            compact('peminjaman')
        );
    }

    public function create()
    {
        Anggota::updateStatusSanksi();

        $anggota = Anggota::where('status_anggota', 'Aktif')
                        ->orderBy('nama')
                        ->get();

        $alat = AlatMusik::whereIn('status_alat', ['Tersedia', 'tersedia'])
                        ->orderBy('nama_alat')
                        ->get();

        return view(
            'admin.peminjaman.create',
            compact('anggota', 'alat')
        );
    }

    public function store(Request $request)
    {
        Anggota::updateStatusSanksi();

        $request->validate([
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'id_alat'    => 'required',
        ]);

        $anggota = Anggota::findOrFail($request->id_anggota);
        if (strtolower($anggota->status_anggota) !== 'aktif') {
            return redirect()->back()->with('error', 'Peminjaman ditolak: Status anggota "' . $anggota->nama . '" saat ini adalah ' . strtoupper($anggota->status_anggota) . '. Anggota masih memiliki sanksi atau denda yang belum diselesaikan.');
        }

        // Rule 1 & 4: Check if member already has an ACTIVE borrowing transaction
        $hasActiveTransaction = Peminjaman::where('id_anggota', $request->id_anggota)
            ->where(function($q) {
                $q->where('status_transaksi', 'Aktif')
                  ->orWhere('status_pinjam', 'dipinjam');
            })
            ->exists();

        if ($hasActiveTransaction) {
            return redirect()->back()->with('error', 'Anggota masih memiliki transaksi peminjaman yang belum selesai. Silakan selesaikan transaksi tersebut terlebih dahulu.');
        }

        // Normalize array of instrument IDs
        $alatIds = is_array($request->id_alat) ? $request->id_alat : [$request->id_alat];
        $alatIds = array_unique(array_filter($alatIds));

        if (empty($alatIds)) {
            return redirect()->back()->with('error', 'Silakan pilih atau scan minimal satu alat musik untuk dipinjam.');
        }

        // Verify all instruments are Available ('Tersedia')
        $alats = AlatMusik::whereIn('id_alat', $alatIds)->get();
        if ($alats->count() !== count($alatIds)) {
            return redirect()->back()->with('error', 'Salah satu atau lebih alat musik tidak ditemukan.');
        }

        foreach ($alats as $itemAlat) {
            if (!in_array($itemAlat->status_alat, ['Tersedia', 'tersedia'])) {
                return redirect()->back()->with('error', 'Peminjaman ditolak: Alat musik "' . $itemAlat->nama_alat . '" berstatus "' . $itemAlat->status_alat . '" dan tidak tersedia untuk dipinjam.');
            }
        }

        try {
            DB::beginTransaction();

            $tanggalPinjam = Carbon::today();
            $maxDays = $alats->max('maks_lama_pinjam') ?? 1;
            $batasKembali = Carbon::today()->addDays($maxDays);

            // Generate Kode Transaksi Unik (TRX-YYYYMMDDXXX)
            $datePrefix = 'TRX-' . date('Ymd');
            $latestTrx = Peminjaman::where('kode_transaksi', 'like', $datePrefix . '%')->latest('id_peminjaman')->first();
            $nextSeq = 1;
            if ($latestTrx && preg_match('/TRX-\d{8}(\d+)$/', $latestTrx->kode_transaksi, $matches)) {
                $nextSeq = ((int) $matches[1]) + 1;
            }
            $kodeTransaksi = $datePrefix . sprintf('%03d', $nextSeq);

            // 1. Create Header Transaksi
            $peminjaman = Peminjaman::create([
                'kode_transaksi'   => $kodeTransaksi,
                'id_anggota'       => $request->id_anggota,
                'id_alat'          => $alats->first()->id_alat, // Legacy compatibility
                'id_admin'         => session('id_admin'),
                'tanggal_pinjam'   => $tanggalPinjam,
                'batas_kembali'    => $batasKembali,
                'status_pinjam'    => 'dipinjam',
                'status_transaksi' => 'Aktif',
            ]);

            // 2. Create Detail Alat Transaksi
            $namaAlatList = [];
            foreach ($alats as $itemAlat) {
                \App\Models\DetailPeminjaman::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_alat'       => $itemAlat->id_alat,
                    'status_detail' => 'dipinjam',
                ]);

                $itemAlat->update([
                    'status_alat' => 'Dipinjam'
                ]);

                $namaAlatList[] = $itemAlat->nama_alat . " ({$itemAlat->kode_alat})";
            }

            // Record Audit Trail
            $strAlat = implode(', ', $namaAlatList);
            \App\Models\ActivityLog::record(
                'Transaksi Peminjaman Baru',
                'Peminjaman',
                "Transaksi #{$kodeTransaksi} ({$alats->count()} Alat: {$strAlat}) berhasil dibuat untuk anggota {$anggota->nama} (NIM: {$anggota->nim})."
            );

            DB::commit();
            \Illuminate\Support\Facades\Cache::forget('peminjaman_active_state');

            return redirect()
                ->route('admin.peminjaman.index')
                ->with('success', "Transaksi peminjaman #{$kodeTransaksi} ({$alats->count()} alat) berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses peminjaman: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with([
            'anggota',
            'details.alat',
            'alat',
            'admin'
        ])->findOrFail($id);

        return view(
            'admin.peminjaman.show',
            compact('peminjaman')
        );
    }
}