<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Peminjaman;
use App\Models\AlatMusik;
use App\Models\Pengembalian;
use App\Models\CatatanPelanggaran;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with([
                'anggota',
                'details.alat',
                'alat'
            ])
            ->where(function($q) {
                $q->where('status_transaksi', 'Aktif')
                  ->orWhere('status_pinjam', 'dipinjam');
            })
            ->latest()
            ->get();

        return view(
            'admin.pengembalian.index',
            compact('peminjaman')
        );
    }

    public function create()
    {
        return view('admin.pengembalian.create');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with([
            'anggota',
            'details.alat',
            'alat'
        ])->findOrFail($id);

        $autoDetail = request()->query('auto_detail');
        $autoScan = request()->query('auto_scan');

        \Illuminate\Support\Facades\Cache::put('pengembalian_active_state', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_detail'     => $autoDetail ?: null,
            'auto_scan'     => $autoScan ?: null,
            'updated_at'    => microtime(true),
            'action'        => 'open_detail'
        ], 60);

        return view(
            'admin.pengembalian.show',
            compact('peminjaman')
        );
    }

    public function store(Request $request)
    {
        // Sanitize thousand dot separators (e.g. 500.000 -> 500000)
        if ($request->has('denda_kerusakan') && $request->denda_kerusakan !== null) {
            $raw = (string) $request->denda_kerusakan;
            $cleaned = str_replace('.', '', $raw);
            $cleaned = str_replace(',', '.', $cleaned);
            $request->merge(['denda_kerusakan' => $cleaned]);
        }

        $rules = [
            'id_peminjaman'   => 'required|exists:peminjaman,id_peminjaman',
            'id_detail'       => 'nullable|exists:detail_peminjaman,id_detail',
            'id_alat'         => 'nullable|exists:alat,id_alat',
            'kondisi_alat'    => 'required|in:Baik,Rusak Ringan,Rusak Berat,Hilang',
            'denda_kerusakan' => 'nullable|numeric|min:0',
            'keterangan'      => 'nullable|string',
        ];

        if (in_array($request->kondisi_alat, ['Rusak Ringan', 'Rusak Berat', 'Hilang'])) {
            $rules['denda_kerusakan'] = 'required|numeric|gt:0';
            $rules['keterangan']      = 'required|string';
        } else {
            $request->merge(['denda_kerusakan' => 0]);
        }

        $messages = [
            'denda_kerusakan.required' => 'Nominal ganti rugi wajib diisi dan harus lebih dari Rp0 untuk alat yang rusak atau hilang.',
            'denda_kerusakan.gt'       => 'Nominal ganti rugi wajib diisi dan harus lebih dari Rp0 untuk alat yang rusak atau hilang.',
            'denda_kerusakan.numeric'  => 'Nominal ganti rugi harus berupa angka yang valid.',
            'keterangan.required'      => 'Catatan / keterangan wajib diisi untuk alat yang rusak atau hilang.',
        ];

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::with(['details.alat', 'anggota'])
                            ->findOrFail($request->id_peminjaman);

            // Find specific detail item
            $detail = null;
            if ($request->id_detail) {
                $detail = \App\Models\DetailPeminjaman::with('alat')
                    ->where('id_peminjaman', $peminjaman->id_peminjaman)
                    ->where('id_detail', $request->id_detail)
                    ->first();
            } elseif ($request->id_alat) {
                $detail = \App\Models\DetailPeminjaman::with('alat')
                    ->where('id_peminjaman', $peminjaman->id_peminjaman)
                    ->where('id_alat', $request->id_alat)
                    ->where('status_detail', 'dipinjam')
                    ->first();
            }

            // Fallback to first unreturned detail
            if (!$detail) {
                $detail = \App\Models\DetailPeminjaman::with('alat')
                    ->where('id_peminjaman', $peminjaman->id_peminjaman)
                    ->where('status_detail', 'dipinjam')
                    ->first();
            }

            if (!$detail || $detail->status_detail === 'dikembalikan') {
                return redirect()->back()->with('error', 'Alat musik ini telah dikembalikan sebelumnya atau tidak ditemukan dalam transaksi.');
            }

            $tanggalKembali = Carbon::today();

            // 1. Simpan record pengembalian item
            $pengembalian = Pengembalian::create([
                'id_peminjaman'   => $peminjaman->id_peminjaman,
                'id_detail'       => $detail->id_detail,
                'id_admin'        => session('id_admin'),
                'tanggal_kembali' => $tanggalKembali,
                'kondisi_alat'    => $request->kondisi_alat,
                'keterangan'      => $request->keterangan,
            ]);

            // 2. Update Detail Peminjaman
            $detail->update([
                'status_detail'        => 'dikembalikan',
                'tanggal_dikembalikan' => $tanggalKembali,
                'kondisi_dikembalikan' => $request->kondisi_alat,
            ]);

            // 3. Update Status Alat Musik
            $statusAlatBaru = 'Tersedia';
            if ($request->kondisi_alat === 'Rusak Ringan') {
                $statusAlatBaru = 'Rusak Ringan';
            } elseif ($request->kondisi_alat === 'Rusak Berat') {
                $statusAlatBaru = 'Rusak Berat';
            } elseif ($request->kondisi_alat === 'Hilang') {
                $statusAlatBaru = 'Hilang';
            }

            if ($detail->alat) {
                $detail->alat->update([
                    'status_alat' => $statusAlatBaru,
                ]);
            }

            // 4. Hitung keterlambatan otomatis (per transaksi)
            $batasKembali = Carbon::parse($peminjaman->batas_kembali);
            $hariTerlambat = 0;
            $dendaKeterlambatan = 0;

            if ($tanggalKembali->gt($batasKembali)) {
                $hariTerlambat = (int) $batasKembali->diffInDays($tanggalKembali);
                $dendaKeterlambatan = $hariTerlambat * 10000;
            }

            // Nominal Denda Kerusakan / Kehilangan
            $dendaKerusakan = 0;
            if ($request->kondisi_alat !== 'Baik') {
                $dendaKerusakan = (float) ($request->denda_kerusakan ?? 0);
            }

            // 5. Catat Pelanggaran jika ada denda / rusak / hilang
            $listPelanggaran = [];
            if ($hariTerlambat > 0) {
                $listPelanggaran[] = 'Keterlambatan (' . $hariTerlambat . ' Hari)';
            }
            if (in_array($request->kondisi_alat, ['Rusak Ringan', 'Rusak Berat'])) {
                $listPelanggaran[] = 'Kerusakan (' . $request->kondisi_alat . ')';
            } elseif ($request->kondisi_alat === 'Hilang') {
                $listPelanggaran[] = 'Kehilangan';
            }

            $totalDenda = $dendaKeterlambatan + $dendaKerusakan;

            if (!empty($listPelanggaran) || $totalDenda > 0) {
                $strJenisPelanggaran = implode(', ', $listPelanggaran);

                $sanksiText = 'Ditangguhkan (Sanksi & Denda Tertunggak)';
                if ($hariTerlambat >= 30) {
                    $sanksiText = 'Keanggotaan Dinonaktifkan (Keterlambatan >= 30 Hari)';
                }

                CatatanPelanggaran::create([
                    'id_pengembalian'      => $pengembalian->id_pengembalian,
                    'id_anggota'           => $peminjaman->id_anggota,
                    'batas_pengembalian'   => $peminjaman->batas_kembali,
                    'tanggal_pengembalian' => $tanggalKembali,
                    'hari_terlambat'       => $hariTerlambat,
                    'denda_keterlambatan'  => $dendaKeterlambatan,
                    'denda_kerusakan'      => $dendaKerusakan,
                    'jenis_pelanggaran'    => $strJenisPelanggaran,
                    'keterangan'           => $request->keterangan,
                    'sanksi'               => $sanksiText,
                    'status_pembayaran'    => 'Belum Lunas',
                ]);
            }

            // 6. Check if all items in the transaction are returned (Rules 6 & 7)
            $unreturnedCount = \App\Models\DetailPeminjaman::where('id_peminjaman', $peminjaman->id_peminjaman)
                ->where('status_detail', 'dipinjam')
                ->count();

            if ($unreturnedCount === 0) {
                $peminjaman->update([
                    'status_transaksi' => 'Selesai',
                    'status_pinjam'    => 'dikembalikan',
                ]);
                $msgStatus = 'Seluruh alat dalam transaksi telah dikembalikan. Status transaksi kini SELESAI.';
            } else {
                $peminjaman->update([
                    'status_transaksi' => 'Aktif',
                    'status_pinjam'    => 'dipinjam',
                ]);
                $msgStatus = "Alat " . ($detail->alat->nama_alat ?? 'Musik') . " berhasil dikembalikan. Sisa {$unreturnedCount} alat masih dipinjam (Status Transaksi: AKTIF).";
            }

            // 7. Evaluasi & Update Status Anggota via Centralized Sync
            Anggota::syncStatus($peminjaman->id_anggota);

            // Record Audit Trail
            $namaAlat = $detail->alat->nama_alat ?? 'Alat';
            $namaAnggota = $peminjaman->anggota->nama ?? 'Anggota';
            \App\Models\ActivityLog::record(
                'Pengembalian Alat',
                'Pengembalian',
                "Pengembalian alat '{$namaAlat}' untuk Transaksi #{$peminjaman->kode_transaksi} oleh {$namaAnggota}. Kondisi: {$request->kondisi_alat}. Status Transaksi: {$peminjaman->status_transaksi}."
            );

            DB::commit();

            if ($unreturnedCount === 0) {
                \Illuminate\Support\Facades\Cache::put('pengembalian_active_state', [
                    'id_peminjaman' => null,
                    'id_detail'     => null,
                    'auto_scan'     => null,
                    'updated_at'    => microtime(true),
                    'action'        => 'completed'
                ], 60);

                return redirect()
                    ->route('admin.pengembalian.index')
                    ->with('success', $msgStatus);
            } else {
                \Illuminate\Support\Facades\Cache::put('pengembalian_active_state', [
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_detail'     => null,
                    'auto_scan'     => null,
                    'updated_at'    => microtime(true),
                    'action'        => 'item_returned'
                ], 60);

                return redirect()
                    ->route('admin.pengembalian.show', $peminjaman->id_peminjaman)
                    ->with('success', $msgStatus);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    public function findByBarcode(Request $request, $code)
    {
        $code = trim($code);

        $alat = AlatMusik::where('barcode_alat', $code)
            ->orWhere('kode_alat', $code)
            ->first();

        if (!$alat) {
            return response()->json([
                'success' => false,
                'message' => 'Data alat tidak ditemukan.'
            ], 404);
        }

        $targetPeminjamanId = $request->query('id_peminjaman') ?? $request->input('id_peminjaman');
        $targetAnggotaId = $request->query('id_anggota') ?? $request->input('id_anggota');

        if ($targetPeminjamanId) {
            $peminjaman = Peminjaman::with('anggota')->find($targetPeminjamanId);

            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data transaksi peminjaman tidak ditemukan.'
                ], 404);
            }

            // Find detail for this specific alat in this transaction
            $detail = \App\Models\DetailPeminjaman::where('id_peminjaman', $targetPeminjamanId)
                ->where('id_alat', $alat->id_alat)
                ->first();

            if (!$detail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barcode alat tidak sesuai dengan transaksi peminjaman anggota ini.'
                ], 422);
            }

            if ($detail->status_detail === 'dikembalikan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat ini sudah dikembalikan.'
                ], 422);
            }

            $detailId = $detail->id_detail ?? '';
            $redirectUrl = route('admin.pengembalian.show', $peminjaman->id_peminjaman) . '?auto_detail=' . $detailId . '&auto_scan=' . urlencode($code);

            \Illuminate\Support\Facades\Cache::put('pengembalian_active_state', [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_detail'     => $detail->id_detail ?? null,
                'auto_scan'     => $code,
                'updated_at'    => microtime(true),
                'action'        => 'open_detail'
            ], 60);

            return response()->json([
                'success'       => true,
                'redirect_url'  => $redirectUrl,
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_detail'     => $detail->id_detail,
                'id_alat'       => $alat->id_alat,
                'kode_alat'     => $alat->kode_alat,
                'nama_alat'     => $alat->nama_alat,
                'id_anggota'    => $peminjaman->id_anggota,
                'nama_anggota'  => $peminjaman->anggota->nama ?? '-',
                'message'       => 'Alat ' . $alat->nama_alat . ' ditemukan dan valid dalam transaksi ini.'
            ]);
        }

        // Default lookup for global scan (create.blade.php)
        $detail = \App\Models\DetailPeminjaman::with(['peminjaman.anggota', 'alat'])
            ->where('id_alat', $alat->id_alat)
            ->where('status_detail', 'dipinjam')
            ->latest('id_detail')
            ->first();

        if (!$detail || !$detail->peminjaman) {
            // Check legacy fallback
            $peminjaman = Peminjaman::with('anggota')
                ->where('id_alat', $alat->id_alat)
                ->where('status_pinjam', 'dipinjam')
                ->latest()
                ->first();

            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat ini sudah dikembalikan.'
                ], 404);
            }
        } else {
            $peminjaman = $detail->peminjaman;
        }

        if ($targetAnggotaId && (int)$peminjaman->id_anggota !== (int)$targetAnggotaId) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode alat tidak sesuai dengan transaksi peminjaman anggota ini.'
            ], 422);
        }

        $detailId = $detail->id_detail ?? '';
        $redirectUrl = route('admin.pengembalian.show', $peminjaman->id_peminjaman) . '?auto_detail=' . $detailId . '&auto_scan=' . urlencode($code);

        \Illuminate\Support\Facades\Cache::put('pengembalian_active_state', [
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_detail'     => $detail->id_detail ?? null,
            'auto_scan'     => $code,
            'updated_at'    => microtime(true),
            'action'        => 'open_detail'
        ], 60);

        return response()->json([
            'success'       => true,
            'redirect_url'  => $redirectUrl,
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_detail'     => $detail->id_detail ?? null,
            'id_alat'       => $alat->id_alat,
            'kode_alat'     => $alat->kode_alat,
            'nama_alat'     => $alat->nama_alat,
            'id_anggota'    => $peminjaman->id_anggota,
            'nama_anggota'  => $peminjaman->anggota->nama ?? '-',
            'message'       => 'Peminjaman ditemukan untuk alat: ' . $alat->nama_alat
        ]);
    }
}