@extends('layouts.app')

@section('title','Data Peminjaman')

@section('content')

<div class="card border-0 shadow-sm" style="border-radius: 18px;">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill mb-1">TRANSACTION HISTORY</span>
            <h4 class="fw-bold text-dark m-0">
                <i class="bi bi-box-arrow-in-down text-success me-2"></i> Data Peminjaman Alat Musik
            </h4>
        </div>
        <div>
            <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Tambah Peminjaman
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Live Table Search Input -->
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="peminjaman_search" class="form-control bg-light border-start-0 ps-0" placeholder="Cari nama anggota, kode/nama alat...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="table_peminjaman">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>Peminjam (Anggota)</th>
                        <th>Alat Musik</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $item)
                        <tr class="peminjaman-row-item">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong class="d-block text-dark">{{ $item->anggota->nama ?? '-' }}</strong>
                                <small class="text-muted">NIM: {{ $item->anggota->nim ?? '-' }}</small>
                            </td>
                            <td>
                                @if($item->details && $item->details->count() > 0)
                                    @foreach($item->details as $detail)
                                        <div class="mb-1">
                                            <span class="badge bg-success-subtle text-success border border-success fw-bold">
                                                <i class="bi bi-music-note me-1"></i> {{ $detail->alat->nama_alat ?? '-' }}
                                            </span>
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
                            <td>
                                @if($item->status_pinjam === 'dipinjam')
                                    <span class="badge bg-warning text-dark">🟡 Dipinjam</span>
                                @else
                                    <span class="badge bg-success">🟢 Dikembalikan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.peminjaman.show', $item->id_peminjaman) }}" class="btn btn-info btn-sm text-white px-3">
                                    <i class="bi bi-eye-fill me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada transaksi peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('peminjaman_search');
    const tableRows = document.querySelectorAll('.peminjaman-row-item');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            tableRows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }
});
</script>

@endsection