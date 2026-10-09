@extends('layouts.app')

@section('title','Data Alat Musik')

@section('content')

<div class="card border-0 shadow-sm" style="border-radius: 18px;">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill mb-1">INVENTORY MANAGEMENT</span>
            <h4 class="fw-bold text-dark m-0">
                <i class="bi bi-music-note-beamed text-success me-2"></i> Data Alat Musik
            </h4>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.alat.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Tambah Alat Musik
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Live Table Search & Status Filter -->
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="table_search" class="form-control bg-light border-start-0 ps-0" placeholder="Cari kode atau nama alat musik...">
                </div>
            </div>
            <div class="col-md-7">
                <div class="d-flex flex-wrap gap-2 justify-content-md-end" id="status_filter_alat">
                    <button type="button" class="btn btn-sm btn-outline-success active btn-filter-alat" data-status="all">
                        <i class="bi bi-grid-fill me-1"></i> Semua
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success btn-filter-alat" data-status="tersedia">
                        🟢 Tersedia
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning text-dark btn-filter-alat" data-status="dipinjam">
                        🟡 Dipinjam
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-filter-alat" data-status="rusak">
                        🔴 Rusak
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-dark btn-filter-alat" data-status="hilang">
                        ❌ Hilang
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="table_alat">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>Foto</th>
                        <th>Kode</th>
                        <th>Nama Alat Musik</th>
                        <th>Kategori</th>
                        <th>Barcode Text</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alat as $item)
                        @php $stValLower = strtolower($item->status_alat); @endphp
                        <tr class="table-row-item" data-status="{{ $stValLower }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($item->foto_alat)
                                    <img src="{{ asset('uploads/alat/'.$item->foto_alat) }}" class="rounded-3" style="width: 48px; height: 48px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                                        <i class="bi bi-music-note"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong class="text-primary font-monospace">{{ $item->kode_alat }}</strong></td>
                            <td class="fw-semibold text-dark">{{ $item->nama_alat }}</td>
                            <td><span class="badge bg-light text-dark">{{ $item->kategori }}</span></td>
                            <td><small class="text-muted font-monospace"><i class="bi bi-barcode me-1"></i>{{ $item->barcode_alat ?? $item->kode_alat }}</small></td>
                            <td>
                                @php $st = strtolower($item->status_alat); @endphp
                                @if($st === 'tersedia')
                                    <span class="badge bg-success">🟢 Tersedia</span>
                                @elseif($st === 'dipinjam')
                                    <span class="badge bg-warning text-dark">🟡 Dipinjam</span>
                                @elseif($st === 'rusak ringan')
                                    <span class="badge bg-warning text-dark">🔴 Rusak Ringan</span>
                                @elseif($st === 'rusak berat' || $st === 'rusak')
                                    <span class="badge bg-danger">🔴 Rusak Berat</span>
                                @elseif($st === 'hilang')
                                    <span class="badge bg-dark">❌ Hilang</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->status_alat }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group gap-1">
                                    <a href="{{ route('admin.alat.show', $item->id_alat) }}" class="btn btn-info btn-sm text-white" title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i> Detail
                                    </a>
                                    <a href="{{ route('admin.alat.cetak_barcode', $item->id_alat) }}" class="btn btn-gold btn-sm" target="_blank" title="Cetak Barcode">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                    <a href="{{ route('admin.alat.edit', $item->id_alat) }}" class="btn btn-warning btn-sm" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data alat musik yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('table_search');
    const filterButtonsAlat = document.querySelectorAll('.btn-filter-alat');
    const tableRows = document.querySelectorAll('.table-row-item');
    let currentStatusAlat = 'all';

    function filterAlatTable() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

        tableRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const status = row.dataset.status ? row.dataset.status.toLowerCase() : '';

            const matchesQuery = !query || text.includes(query);
            let matchesStatus = (currentStatusAlat === 'all');
            
            if (currentStatusAlat === 'tersedia') {
                matchesStatus = (status === 'tersedia');
            } else if (currentStatusAlat === 'dipinjam') {
                matchesStatus = (status === 'dipinjam');
            } else if (currentStatusAlat === 'rusak') {
                matchesStatus = status.includes('rusak');
            } else if (currentStatusAlat === 'hilang') {
                matchesStatus = (status === 'hilang');
            }

            if (matchesQuery && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterAlatTable);
    }

    filterButtonsAlat.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtonsAlat.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentStatusAlat = this.dataset.status ? this.dataset.status.toLowerCase() : 'all';
            filterAlatTable();
        });
    });
});
</script>

@endsection