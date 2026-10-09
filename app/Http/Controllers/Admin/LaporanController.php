<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\CatatanPelanggaran;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data = collect();

        if ($request->jenis == 'peminjaman') {
            $data = Peminjaman::with([
                'anggota',
                'details.alat',
                'alat'
            ])
            ->when($request->tanggal_awal, function ($query) use ($request) {
                $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_awal);
            })
            ->when($request->tanggal_akhir, function ($query) use ($request) {
                $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_akhir);
            })
            ->latest('id_peminjaman')
            ->get();
        }
        elseif ($request->jenis == 'pengembalian') {
            $data = Pengembalian::with([
                'peminjaman.anggota',
                'peminjaman.details.alat',
                'detail.alat',
                'admin'
            ])
            ->when($request->tanggal_awal, function ($query) use ($request) {
                $query->whereDate('tanggal_kembali', '>=', $request->tanggal_awal);
            })
            ->when($request->tanggal_akhir, function ($query) use ($request) {
                $query->whereDate('tanggal_kembali', '<=', $request->tanggal_akhir);
            })
            ->latest('id_pengembalian')
            ->get();
        }
        elseif ($request->jenis == 'pelanggaran') {
            $data = CatatanPelanggaran::with([
                'anggota',
                'pengembalian.detail.alat',
                'pengembalian.peminjaman.details.alat'
            ])
            ->when($request->tanggal_awal, function ($query) use ($request) {
                $query->whereDate('tanggal_pengembalian', '>=', $request->tanggal_awal);
            })
            ->when($request->tanggal_akhir, function ($query) use ($request) {
                $query->whereDate('tanggal_pengembalian', '<=', $request->tanggal_akhir);
            })
            ->latest('id_pelanggaran')
            ->get();
        }

        return view('admin.laporan.index', compact('data'));
    }

    public function pdf(Request $request)
    {
        $data = collect();

        if ($request->jenis == 'peminjaman') {
            $data = Peminjaman::with([
                'anggota',
                'details.alat',
                'alat'
            ])
            ->when($request->tanggal_awal, function ($query) use ($request) {
                $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_awal);
            })
            ->when($request->tanggal_akhir, function ($query) use ($request) {
                $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_akhir);
            })
            ->latest('id_peminjaman')
            ->get();
        } elseif ($request->jenis == 'pengembalian') {
            $data = Pengembalian::with([
                'peminjaman.anggota',
                'peminjaman.details.alat',
                'detail.alat',
                'admin'
            ])
            ->when($request->tanggal_awal, function ($query) use ($request) {
                $query->whereDate('tanggal_kembali', '>=', $request->tanggal_awal);
            })
            ->when($request->tanggal_akhir, function ($query) use ($request) {
                $query->whereDate('tanggal_kembali', '<=', $request->tanggal_akhir);
            })
            ->latest('id_pengembalian')
            ->get();
        } elseif ($request->jenis == 'pelanggaran') {
            $data = CatatanPelanggaran::with([
                'anggota',
                'pengembalian.detail.alat',
                'pengembalian.peminjaman.details.alat'
            ])
            ->when($request->tanggal_awal, function ($query) use ($request) {
                $query->whereDate('tanggal_pengembalian', '>=', $request->tanggal_awal);
            })
            ->when($request->tanggal_akhir, function ($query) use ($request) {
                $query->whereDate('tanggal_pengembalian', '<=', $request->tanggal_akhir);
            })
            ->latest('id_pelanggaran')
            ->get();
        }

        $pdf = Pdf::loadView('admin.laporan.pdf', [
            'data' => $data,
            'jenis' => $request->jenis,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('laporan.pdf');
    }
}
