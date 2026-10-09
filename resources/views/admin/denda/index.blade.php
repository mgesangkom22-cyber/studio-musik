@extends('layouts.app')

@section('title','Pembayaran Denda Anggota')

@section('content')

@php
    $unpaidCount = $dendaList->whereIn('status_pembayaran', ['Belum Lunas', 'Belum Dibayar'])->count();
    $paidCount = $dendaList->whereIn('status_pembayaran', ['Lunas', 'Sudah Dibayar'])->count();
    $totalUnpaidNominal = $dendaList->whereIn('status_pembayaran', ['Belum Lunas', 'Belum Dibayar'])->sum(function($item) {
        return $item->sisa_denda;
    });
@endphp

<!-- Stat Summary Banner -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #ef4444 !important; border-radius: 16px;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3 text-white d-flex align-items-center justify-content-center" style="background: rgba(239, 68, 68, 0.15); width: 55px; height: 55px;">
                    <i class="bi bi-exclamation-circle-fill fs-3 text-danger"></i>
                </div>
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Sisa Denda Belum Lunas</span>
                    <h3 class="fw-bold mb-0 text-danger">{{ $unpaidCount }} Record <small class="fs-6 text-muted">(Rp {{ number_format($totalUnpaidNominal, 0, ',', '.') }})</small></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #10b981 !important; border-radius: 16px;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3 text-white d-flex align-items-center justify-content-center" style="background: rgba(16, 185, 129, 0.15); width: 55px; height: 55px;">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                </div>
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Denda Lunas</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $paidCount }} Record</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 18px;">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-2 rounded-pill mb-1">TRANSAKSI & PELUNASAN</span>
            <h4 class="fw-bold text-dark m-0">
                <i class="bi bi-cash-stack text-danger me-2"></i> Pembayaran & Cicilan Denda
            </h4>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Filter Controls -->
        <form action="{{ route('admin.denda.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Cari nama anggota atau NIM..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="">Semua Status Pembayaran</option>
                    <option value="Belum Lunas" {{ request('status') == 'Belum Lunas' ? 'selected' : '' }}>🔴 Belum Lunas</option>
                    <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>🟢 Lunas</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success flex-fill"><i class="bi bi-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.denda.index') }}" class="btn btn-light" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Anggota</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Rincian Denda</th>
                        <th>Total & Sisa Denda</th>
                        <th>Status Pembayaran</th>
                        <th>Tgl Pengembalian</th>
                        <th class="text-center">Aksi & Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dendaList as $item)
                        @php
                            $totalDenda = $item->denda_keterlambatan + $item->denda_kerusakan;
                            $totalDibayar = $item->total_dibayar;
                            $sisaDenda = $item->sisa_denda;
                            $isPaid = in_array($item->status_pembayaran, ['Lunas', 'Sudah Dibayar']);
                            $riwayatCount = $item->riwayatPembayaran->count();
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong class="d-block text-dark">{{ $item->anggota->nama ?? '-' }}</strong>
                                <small class="text-muted">NIM: {{ $item->anggota->nim ?? '-' }}</small>
                            </td>
                            <td><span class="badge bg-danger-subtle text-danger">{{ $item->jenis_pelanggaran }}</span></td>
                            <td>
                                <small class="d-block text-muted">Terlambat: Rp {{ number_format($item->denda_keterlambatan, 0, ',', '.') }}</small>
                                <small class="d-block text-muted">Kerusakan: Rp {{ number_format($item->denda_kerusakan, 0, ',', '.') }}</small>
                            </td>
                            <td>
                                <strong class="text-dark d-block">Total: Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong>
                                @if($sisaDenda > 0)
                                    <small class="text-danger fw-bold d-block">Sisa: Rp {{ number_format($sisaDenda, 0, ',', '.') }}</small>
                                @else
                                    <small class="text-success fw-bold d-block">Sisa: Rp 0 (Lunas)</small>
                                @endif
                            </td>
                            <td>
                                @if($isPaid)
                                    <span class="badge bg-success fs-6 px-3 py-1">🟢 Lunas</span>
                                @elseif($totalDibayar > 0)
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-1">🟡 Belum Lunas (Cicil)</span>
                                @else
                                    <span class="badge bg-danger fs-6 px-3 py-1">🔴 Belum Lunas</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d-m-Y') }}</small></td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                    @if(!$isPaid)
                                        <button type="button" class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalBayar{{ $item->id_pelanggaran }}">
                                            <i class="bi bi-cash-coin me-1"></i> Bayar
                                        </button>
                                    @else
                                        <span class="badge bg-light text-success border me-1"><i class="bi bi-check-all me-1"></i> Lunas</span>
                                    @endif

                                    @if($riwayatCount > 0)
                                        <button type="button" class="btn btn-outline-info btn-sm px-2" data-bs-toggle="modal" data-bs-target="#modalRiwayat{{ $item->id_pelanggaran }}" title="Lihat Riwayat & Bukti Pembayaran">
                                            <i class="bi bi-receipt me-1"></i> Riwayat ({{ $riwayatCount }})
                                        </button>
                                    @endif
                                </div>

                                <!-- Modal Form Input Pembayaran Denda (Gradual Payment + Upload Bukti) -->
                                <div class="modal fade text-start" id="modalBayar{{ $item->id_pelanggaran }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title fw-bold"><i class="bi bi-cash-coin me-2"></i> Form Pembayaran Denda</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.denda.bayar', $item->id_pelanggaran) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body p-4">
                                                    <!-- Detail Summary -->
                                                    <div class="p-3 bg-light rounded-3 mb-3 border">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="text-muted">Anggota:</span>
                                                            <strong class="text-dark">{{ $item->anggota->nama ?? '-' }}</strong>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="text-muted">Total Denda:</span>
                                                            <strong class="text-dark">Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="text-muted">Sudah Dibayar:</span>
                                                            <span class="text-success fw-bold">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                                                        </div>
                                                        <hr class="my-2">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="fw-bold text-dark">Sisa Denda:</span>
                                                            <strong class="text-danger fs-5">Rp {{ number_format($sisaDenda, 0, ',', '.') }}</strong>
                                                        </div>
                                                    </div>

                                                    <!-- Input Nominal Pembayaran -->
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark">Nominal Pembayaran (Rp)</label>
                                                        <input type="number" 
                                                               name="nominal_pembayaran" 
                                                               class="form-control form-control-lg fw-bold text-success" 
                                                               value="{{ $sisaDenda }}" 
                                                               max="{{ $sisaDenda }}" 
                                                               min="1" 
                                                               required>
                                                        <small class="text-muted">Bisa diisi sebagian untuk pembayaran bertahap/cicilan.</small>
                                                    </div>

                                                    <!-- Upload Bukti Pembayaran -->
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark">
                                                            <i class="bi bi-upload me-1 text-primary"></i> Upload Bukti Pembayaran
                                                        </label>
                                                        <input type="file" 
                                                               name="bukti_pembayaran" 
                                                               class="form-control" 
                                                               accept=".jpg,.jpeg,.png,.pdf">
                                                        <small class="text-muted d-block mt-1">Format yang didukung: JPG, PNG, PDF (Maks. 5MB).</small>
                                                    </div>

                                                    <!-- Input Keterangan -->
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark">Keterangan / Catatan</label>
                                                        <input type="text" 
                                                               name="keterangan" 
                                                               class="form-control" 
                                                               placeholder="Contoh: Pelunasan tunai di studio / Transfer Mandiri">
                                                    </div>

                                                    <small class="text-muted d-block">
                                                        <i class="bi bi-info-circle me-1 text-primary"></i> Jika total pembayaran sudah mencapai lunas dan tidak ada denda lain, status anggota akan otomatis kembali menjadi <strong>Aktif</strong>.
                                                    </small>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success px-4 fw-bold"><i class="bi bi-check-circle me-1"></i> Simpan Pembayaran</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Riwayat & Bukti Pembayaran -->
                                @if($riwayatCount > 0)
                                    <div class="modal fade text-start" id="modalRiwayat{{ $item->id_pelanggaran }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i> Riwayat & Bukti Pembayaran Denda</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <strong class="d-block text-dark fs-6">{{ $item->anggota->nama ?? '-' }} (NIM: {{ $item->anggota->nim ?? '-' }})</strong>
                                                        <small class="text-muted">Total Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }} | Sisa Denda: Rp {{ number_format($sisaDenda, 0, ',', '.') }}</small>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm align-middle mb-0">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Tanggal</th>
                                                                    <th>Nominal</th>
                                                                    <th>Admin</th>
                                                                    <th>Keterangan</th>
                                                                    <th class="text-center">Bukti Pembayaran</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($item->riwayatPembayaran as $riwayat)
                                                                    <tr>
                                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                                        <td>{{ \Carbon\Carbon::parse($riwayat->tanggal_pembayaran)->format('d-m-Y') }}</td>
                                                                        <td><strong class="text-success">Rp {{ number_format($riwayat->nominal_pembayaran, 0, ',', '.') }}</strong></td>
                                                                        <td><small>{{ $riwayat->admin->nama_admin ?? 'Admin' }}</small></td>
                                                                        <td><small class="text-muted">{{ $riwayat->keterangan ?? '-' }}</small></td>
                                                                        <td class="text-center">
                                                                            @if($riwayat->bukti_pembayaran)
                                                                                @php
                                                                                    $ext = strtolower(pathinfo($riwayat->bukti_pembayaran, PATHINFO_EXTENSION));
                                                                                    $fileUrl = asset('uploads/denda/' . $riwayat->bukti_pembayaran);
                                                                                @endphp
                                                                                @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                                                                    <a href="{{ $fileUrl }}" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2">
                                                                                        <i class="bi bi-image me-1"></i> Lihat Gambar
                                                                                    </a>
                                                                                @elseif($ext === 'pdf')
                                                                                    <a href="{{ $fileUrl }}" target="_blank" class="btn btn-xs btn-outline-danger py-1 px-2">
                                                                                        <i class="bi bi-file-earmark-pdf me-1"></i> Buka PDF
                                                                                    </a>
                                                                                @else
                                                                                    <a href="{{ $fileUrl }}" target="_blank" class="btn btn-xs btn-outline-secondary py-1 px-2">
                                                                                        <i class="bi bi-download me-1"></i> Unduh File
                                                                                    </a>
                                                                                @endif
                                                                            @else
                                                                                <span class="text-muted small">Tanpa File</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light py-2">
                                                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Tidak ada data denda yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
