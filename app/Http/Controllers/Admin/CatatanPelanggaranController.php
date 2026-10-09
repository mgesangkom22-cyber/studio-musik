<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatatanPelanggaran;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CatatanPelanggaranController extends Controller
{
    public function index()
    {
        Anggota::updateStatusSanksi();

        $pelanggaran = CatatanPelanggaran::with([
            'anggota',
            'pengembalian.detail.alat',
            'pengembalian.peminjaman.details.alat'
        ])
        ->latest()
        ->get();

        return view(
            'admin.pelanggaran.index',
            compact('pelanggaran')
        );
    }

    public function dendaIndex(Request $request)
    {
        Anggota::updateStatusSanksi();

        $query = CatatanPelanggaran::with(['anggota', 'pengembalian.detail.alat', 'pengembalian.peminjaman.details.alat', 'riwayatPembayaran.admin'])
            ->latest();

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status_pembayaran', $request->status);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('anggota', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $dendaList = $query->get();

        return view('admin.denda.index', compact('dendaList'));
    }

    public function bayarDenda(Request $request, $id)
    {
        $request->validate([
            'nominal_pembayaran' => 'required|numeric|min:1',
            'bukti_pembayaran'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'keterangan'         => 'nullable|string|max:255',
        ], [
            'nominal_pembayaran.required' => 'Nominal pembayaran wajib diisi.',
            'nominal_pembayaran.min'      => 'Nominal pembayaran minimal Rp 1.',
            'bukti_pembayaran.mimes'      => 'Bukti pembayaran harus berformat JPG, PNG, atau PDF.',
            'bukti_pembayaran.max'        => 'Ukuran file bukti pembayaran maksimal 5MB.',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $pelanggaran = CatatanPelanggaran::with(['anggota', 'riwayatPembayaran'])->findOrFail($id);
            $totalDenda = (float) ($pelanggaran->denda_keterlambatan + $pelanggaran->denda_kerusakan);

            $namaBukti = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $namaBukti = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $destinationPath = public_path('uploads/denda');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $namaBukti);
            }

            // Record payment entry in PembayaranDenda
            \App\Models\PembayaranDenda::create([
                'id_pelanggaran'     => $pelanggaran->id_pelanggaran,
                'id_admin'           => session('id_admin'),
                'tanggal_pembayaran' => Carbon::today(),
                'nominal_pembayaran' => $request->nominal_pembayaran,
                'bukti_pembayaran'   => $namaBukti,
                'keterangan'         => $request->keterangan ?? 'Pembayaran Denda',
            ]);

            // Re-evaluate total paid
            $totalPaid = (float) \App\Models\PembayaranDenda::where('id_pelanggaran', $pelanggaran->id_pelanggaran)->sum('nominal_pembayaran');

            if ($totalPaid >= $totalDenda) {
                $pelanggaran->update([
                    'status_pembayaran'  => 'Lunas',
                    'tanggal_pembayaran' => Carbon::today(),
                ]);
                $msgStatus = "LUNAS (Total Dibayar: Rp " . number_format($totalPaid, 0, ',', '.') . ")";
            } else {
                $pelanggaran->update([
                    'status_pembayaran' => 'Belum Lunas',
                ]);
                $sisa = $totalDenda - $totalPaid;
                $msgStatus = "Belum Lunas (Sisa: Rp " . number_format($sisa, 0, ',', '.') . ")";
            }

            // Synchronize & re-evaluate member status after payment
            Anggota::syncStatus($pelanggaran->id_anggota);

            $nama = $pelanggaran->anggota->nama ?? 'Anggota';
            \App\Models\ActivityLog::record(
                'Pembayaran Denda',
                'Denda',
                "Pembayaran denda sebesar Rp " . number_format($request->nominal_pembayaran, 0, ',', '.') . " untuk anggota {$nama} (Pelanggaran #{$id}) berhasil diproses. Status: {$msgStatus}."
            );

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->back()->with('success', "Pembayaran denda sebesar Rp " . number_format($request->nominal_pembayaran, 0, ',', '.') . " berhasil disimpan. Status denda saat ini: {$msgStatus}.");

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pembayaran denda: ' . $e->getMessage());
        }
    }
}