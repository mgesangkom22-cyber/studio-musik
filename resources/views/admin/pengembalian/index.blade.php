@extends('layouts.app')

@section('title','Data Pengembalian')

@section('content')

<div class="card shadow border-0" style="border-radius: 18px;">
    <div class="card-header bg-success text-white py-3" style="border-top-left-radius: 18px; border-top-right-radius: 18px;">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-arrow-return-left me-2"></i> Data Pengembalian Alat Musik
            </h4>
            <a href="{{ route('admin.pengembalian.create') }}" class="btn btn-light text-success fw-bold">
                <i class="bi bi-qr-code-scan me-1"></i> Scan Barcode Pengembalian
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-success text-dark">
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>Kode Transaksi & Anggota</th>
                        <th>Daftar Alat Musik dalam Transaksi</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Kembali</th>
                        <th class="text-center" width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $item)
                        @php
                            $totalItems = $item->details ? $item->details->count() : 1;
                            $borrowedCount = $item->details ? $item->details->where('status_detail', 'dipinjam')->count() : ($item->status_pinjam === 'dipinjam' ? 1 : 0);
                        @endphp
                        <tr>
                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                            <td>
                                <strong class="d-block text-dark">{{ $item->anggota->nama ?? '-' }}</strong>
                                <small class="text-muted d-block">NIM: {{ $item->anggota->nim ?? '-' }}</small>
                                <span class="badge bg-secondary font-monospace mt-1">{{ $item->kode_transaksi ?? ('TRX-'.$item->id_peminjaman) }}</span>
                            </td>
                            <td>
                                @if($item->details && $item->details->count() > 0)
                                    <div class="mb-1 text-muted small fw-semibold">
                                        Total {{ $totalItems }} Alat <span class="text-warning">({{ $borrowedCount }} Belum Dikembalikan)</span>
                                    </div>
                                    @foreach($item->details as $detail)
                                        <div class="mb-1">
                                            @if($detail->status_detail === 'dipinjam')
                                                <span class="badge bg-warning text-dark me-1">🟡 Dipinjam</span>
                                            @else
                                                <span class="badge bg-success me-1">🟢 Dikembalikan</span>
                                            @endif
                                            <strong class="text-dark">{{ $detail->alat->nama_alat ?? '-' }}</strong>
                                            <small class="text-muted font-monospace">({{ $detail->alat->kode_alat ?? '-' }})</small>
                                        </div>
                                    @endforeach
                                @else
                                    <strong class="d-block text-success">{{ $item->alat->nama_alat ?? '-' }}</strong>
                                    <small class="text-muted font-monospace">{{ $item->alat->kode_alat ?? '-' }}</small>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $item->tanggal_pinjam }}</small></td>
                            <td><small class="fw-bold text-danger">{{ $item->batas_kembali }}</small></td>
                            <td class="text-center">
                                <a href="{{ route('admin.pengembalian.show', $item->id_peminjaman) }}" class="btn btn-success btn-sm fw-bold px-3">
                                    <i class="bi bi-arrow-return-left me-1"></i> Proses
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada alat yang sedang dipinjam saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection