@extends('layouts.app')

@section('title', 'Data Pelanggaran & Sanksi')

@section('content')

<div class="card border-0 shadow-sm" style="border-radius: 18px;">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-2 rounded-pill mb-1">VIOLATIONS & SANCTIONS</span>
            <h4 class="fw-bold text-dark m-0">
                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> Data Catatan Pelanggaran & Sanksi
            </h4>
        </div>
    </div>

    <div class="card-body p-4">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Anggota</th>
                        <th>Alat Musik</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Hari Terlambat</th>
                        <th>Denda Terlambat</th>
                        <th>Denda Kerusakan</th>
                        <th>Total Denda</th>
                        <th>Sanksi & Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggaran as $item)
                        @php
                            $namaAlat = optional(optional(optional($item->pengembalian)->detail)->alat)->nama_alat
                                ?? optional(optional(optional($item->pengembalian)->peminjaman)->alat)->nama_alat
                                ?? '-';
                            $totalDenda = $item->denda_keterlambatan + $item->denda_kerusakan;
                            $isPaid = in_array($item->status_pembayaran, ['Lunas', 'Sudah Dibayar']);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong class="text-dark d-block">{{ $item->anggota->nama ?? '-' }}</strong>
                                <small class="text-muted">NIM: {{ $item->anggota->nim ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $namaAlat }}</span>
                            </td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger fs-6">{{ $item->jenis_pelanggaran }}</span>
                            </td>
                            <td>
                                @if($item->hari_terlambat > 0)
                                    <span class="badge bg-danger">{{ $item->hari_terlambat }} Hari</span>
                                @else
                                    <span class="badge bg-success">0 Hari</span>
                                @endif
                            </td>
                            <td><small class="text-muted">Rp {{ number_format($item->denda_keterlambatan, 0, ',', '.') }}</small></td>
                            <td><small class="text-muted">Rp {{ number_format($item->denda_kerusakan, 0, ',', '.') }}</small></td>
                            <td><strong class="text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong></td>
                            <td>
                                <div class="mb-1"><small class="text-muted fw-bold">{{ $item->sanksi ?? '-' }}</small></div>
                                @if($isPaid)
                                    <span class="badge bg-success">🟢 Lunas</span>
                                @else
                                    <span class="badge bg-danger">🔴 Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada catatan pelanggaran tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection