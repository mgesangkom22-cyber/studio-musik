@extends('layouts.app')

@section('title','Detail Alat Musik')

@section('content')

<div class="row g-4">
    <!-- Left Column: Tool Photo & Barcode -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 18px;">
            <div class="position-relative overflow-hidden rounded-3 mb-3 bg-light d-flex align-items-center justify-content-center" style="height: 320px;">
                @if($alat->foto_alat)
                    <img src="{{ asset('uploads/alat/'.rawurlencode($alat->foto_alat)) }}" class="img-fluid rounded-3 h-100 w-100" style="object-fit: cover;" alt="{{ $alat->nama_alat }}" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=500&auto=format&fit=crop';">
                @else
                    <div class="text-muted text-center">
                        <i class="bi bi-music-note-beamed display-1"></i>
                        <p class="small mt-2 mb-0">Belum Ada Foto</p>
                    </div>
                @endif
            </div>

            <!-- Barcode Display Badge -->
            <div class="p-3 bg-light rounded-3 border mb-3">
                <span class="text-muted small fw-bold text-uppercase d-block mb-2">Visual Barcode Alat</span>
                @php
                    $barcodeVal = $alat->barcode_alat ?? $alat->kode_alat;
                @endphp
                @if($barcodeVal)
                    <div class="d-inline-block bg-white p-2 border rounded">
                        {!! DNS1D::getBarcodeSVG($barcodeVal, 'C128', 1.8, 55, 'black', false) !!}
                    </div>
                    <small class="d-block mt-2 fw-bold text-dark font-monospace" style="letter-spacing: 2px;">{{ $barcodeVal }}</small>
                @else
                    <span class="badge bg-secondary">Tidak Ada Barcode</span>
                @endif
            </div>

            <!-- Quick Action Buttons -->
            <div class="d-grid gap-2">
                <a href="{{ route('admin.alat.cetak_barcode', $alat->id_alat) }}" class="btn btn-gold text-dark" target="_blank">
                    <i class="bi bi-printer-fill me-1"></i> Cetak Barcode Label
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Detail Information Table & History -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 18px;">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill mb-1">INVENTORY DETAIL</span>
                    <h3 class="fw-bold text-dark m-0">{{ $alat->nama_alat }}</h3>
                </div>
                <div>
                    @php $stAlat = strtolower($alat->status_alat); @endphp
                    @if($stAlat === 'tersedia')
                        <span class="badge bg-success fs-6 px-3 py-2">🟢 Tersedia</span>
                    @elseif($stAlat === 'dipinjam')
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">🟡 Sedang Dipinjam</span>
                    @elseif($stAlat === 'rusak ringan')
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">🔴 Rusak Ringan</span>
                    @elseif($stAlat === 'rusak berat' || $stAlat === 'rusak')
                        <span class="badge bg-danger fs-6 px-3 py-2">🔴 Rusak Berat</span>
                    @elseif($stAlat === 'hilang')
                        <span class="badge bg-dark fs-6 px-3 py-2">❌ Hilang</span>
                    @else
                        <span class="badge bg-secondary fs-6 px-3 py-2">{{ $alat->status_alat }}</span>
                    @endif
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <tr>
                        <th width="35%" class="bg-light text-muted">Kode Alat</th>
                        <td><strong class="text-primary font-monospace">{{ $alat->kode_alat }}</strong></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-muted">Nama Alat Musik</th>
                        <td class="fw-semibold text-dark">{{ $alat->nama_alat }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-muted">Kategori</th>
                        <td><span class="badge bg-info-subtle text-info-emphasis px-3 py-2">{{ $alat->kategori }}</span></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-muted">Maksimal Lama Pinjam</th>
                        <td><strong class="text-success">{{ $alat->maks_lama_pinjam }} Hari</strong></td>
                    </tr>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.alat.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('admin.alat.edit', $alat->id_alat) }}" class="btn btn-warning px-4">
                    <i class="bi bi-pencil-square me-1"></i> Edit Data Alat
                </a>
                <form action="{{ route('admin.alat.destroy', $alat->id_alat) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus alat musik ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-trash me-1"></i> Hapus Alat
                    </button>
                </form>
            </div>
        </div>

        <!-- Riwayat Peminjaman Alat -->
        <div class="card border-0 shadow-sm p-4" style="border-radius: 18px;">
            <h5 class="fw-bold mb-3 text-dark">
                <i class="bi bi-clock-history text-primary me-2"></i> Riwayat Peminjaman Alat Ini
            </h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alat->peminjaman as $history)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $history->anggota->nama ?? '-' }}</strong></td>
                                <td><small class="text-muted">{{ $history->tanggal_pinjam }}</small></td>
                                <td><small class="text-muted">{{ $history->batas_kembali }}</small></td>
                                <td>
                                    @if($history->status_pinjam === 'dipinjam')
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Belum ada riwayat peminjaman untuk alat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection