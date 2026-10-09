<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\AlatMusik;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\CatatanPelanggaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        Anggota::updateStatusSanksi();

        // Metrics Calculation
        $totalAnggota = Anggota::count();
        $totalAlat = AlatMusik::count();
        $alatTersedia = AlatMusik::whereIn('status_alat', ['Tersedia', 'tersedia'])->count();
        $alatDipinjam = AlatMusik::whereIn('status_alat', ['Dipinjam', 'dipinjam'])->count();
        $alatRusak = AlatMusik::whereIn('status_alat', ['Rusak Ringan', 'rusak'])->count();
        $alatHilang = AlatMusik::whereIn('status_alat', ['Hilang', 'hilang'])->count();
        $alatMaintenance = AlatMusik::whereIn('status_alat', ['Rusak Berat', 'perbaikan', 'maintenance'])->count();
        $anggotaSanksi = Anggota::whereIn('status_anggota', ['ditangguhkan', 'nonaktif', 'Ditangguhkan', 'Nonaktif'])->count();

        $transaksiHariIni = Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count();
        $pelanggaranHariIni = CatatanPelanggaran::whereDate('tanggal_pengembalian', Carbon::today())->count();

        // 5 Alat Paling Sering Dipinjam
        $topAlat = AlatMusik::withCount('peminjaman')
                    ->orderBy('peminjaman_count', 'desc')
                    ->take(5)
                    ->get();

        // Pelanggaran Terbaru
        $pelanggaranTerbaru = CatatanPelanggaran::with(['anggota', 'pengembalian'])
                    ->latest()
                    ->take(5)
                    ->get();

        // Transaksi Peminjaman Terbaru
        $peminjamanTerbaru = Peminjaman::with(['anggota', 'alat'])
                    ->latest()
                    ->take(5)
                    ->get();

        // Data Kategori untuk Chart
        $kategoriStats = AlatMusik::selectRaw('kategori, count(*) as count')
                            ->groupBy('kategori')
                            ->pluck('count', 'kategori');

        return view('dashboard.index', compact(
            'totalAnggota',
            'totalAlat',
            'alatTersedia',
            'alatDipinjam',
            'alatRusak',
            'alatHilang',
            'alatMaintenance',
            'anggotaSanksi',
            'transaksiHariIni',
            'pelanggaranHariIni',
            'topAlat',
            'pelanggaranTerbaru',
            'peminjamanTerbaru',
            'kategoriStats'
        ));
    }
}