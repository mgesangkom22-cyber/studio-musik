@extends('layouts.app')

@section('title','Laporan Inventory & Transaksi')

@section('content')

<div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill mb-1">REPORTING & EXPORT</span>
            <h4 class="fw-bold text-dark m-0">
                <i class="bi bi-file-earmark-text-fill text-success me-2"></i> Laporan Studio Musik UNU Yogyakarta
            </h4>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Filter Card Form -->
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="p-3 bg-light rounded-3 border mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Jenis Laporan</label>
                    <select name="jenis" class="form-select" required>
                        <option value="">-- Pilih Jenis Laporan --</option>
                        <option value="peminjaman" {{ request('jenis') == 'peminjaman' ? 'selected' : '' }}>📋 Peminjaman Alat</option>
                        <option value="pengembalian" {{ request('jenis') == 'pengembalian' ? 'selected' : '' }}>🔄 Pengembalian Alat</option>
                        <option value="pelanggaran" {{ request('jenis') == 'pelanggaran' ? 'selected' : '' }}>⚠️ Catatan Pelanggaran</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-fill">
                            <i class="bi bi-funnel-fill me-1"></i> Tampilkan
                        </button>
                        @if(request('jenis'))
                            <a href="{{ route('admin.laporan.pdf', request()->all()) }}" target="_blank" class="btn btn-danger px-3" title="Export File PDF">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        <!-- Preview Results Table -->
        @if(isset($data) && $data->count())
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark m-0">
                    <i class="bi bi-table me-2 text-primary"></i> Preview Data Laporan {{ ucfirst(request('jenis')) }}
                </h5>
                <span class="badge bg-primary px-3 py-2">Total Data: {{ $data->count() }} Record</span>
            </div>

            <div class="table-responsive">
                @if(request('jenis') == 'peminjaman')
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal Pinjam</th>
                                <th>Nama Anggota</th>
                                <th>Alat Musik</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') }}</small></td>
                                    <td><strong>{{ $item->anggota->nama ?? '-' }}</strong></td>
                                    <td>
                                        @if($item->details && $item->details->count() > 0)
                                            <span class="text-success fw-bold">{{ $item->details->map(fn($d) => $d->alat->nama_alat ?? '')->filter()->join(', ') }}</span>
                                        @else
                                            <span class="text-success fw-bold">{{ $item->alat->nama_alat ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td><small class="text-danger fw-bold">{{ \Carbon\Carbon::parse($item->batas_kembali)->format('d-m-Y') }}</small></td>
                                    <td>
                                        @if($item->status_pinjam === 'dipinjam')
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        @else
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif(request('jenis') == 'pengembalian')
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal Kembali</th>
                                <th>Nama Anggota</th>
                                <th>Alat Musik</th>
                                <th>Kondisi Alat</th>
                                <th>Petugas Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') }}</small></td>
                                    <td><strong>{{ $item->peminjaman->anggota->nama ?? '-' }}</strong></td>
                                    <td>
                                        @if($item->detail && $item->detail->alat)
                                            <span class="text-success fw-bold">{{ $item->detail->alat->nama_alat }}</span>
                                        @elseif($item->peminjaman && $item->peminjaman->details && $item->peminjaman->details->count() > 0)
                                            <span class="text-success fw-bold">{{ $item->peminjaman->details->map(fn($d) => $d->alat->nama_alat ?? '')->filter()->join(', ') }}</span>
                                        @else
                                            <span class="text-success fw-bold">{{ $item->peminjaman->alat->nama_alat ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->kondisi_alat === 'Baik')
                                            <span class="badge bg-success">🟢 Baik</span>
                                        @else
                                            <span class="badge bg-danger">🔴 {{ $item->kondisi_alat }}</span>
                                        @endif
                                    </td>
                                    <td><small class="text-muted">{{ $item->admin?->nama_admin ?? '-' }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif(request('jenis') == 'pelanggaran')
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Anggota</th>
                                <th>Alat Musik</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Terlambat</th>
                                <th>Denda Keterlambatan</th>
                                <th>Denda Kerusakan</th>
                                <th>Total Denda</th>
                                <th>Sanksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d-m-Y') }}</small></td>
                                    <td><strong>{{ $item->anggota->nama ?? '-' }}</strong></td>
                                    <td>
                                        @if($item->pengembalian && $item->pengembalian->detail && $item->pengembalian->detail->alat)
                                            <span>{{ $item->pengembalian->detail->alat->nama_alat }}</span>
                                        @elseif($item->pengembalian && $item->pengembalian->peminjaman && $item->pengembalian->peminjaman->details && $item->pengembalian->peminjaman->details->count() > 0)
                                            <span>{{ $item->pengembalian->peminjaman->details->map(fn($d) => $d->alat->nama_alat ?? '')->filter()->join(', ') }}</span>
                                        @else
                                            <span>{{ $item->pengembalian->peminjaman->alat->nama_alat ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-danger">{{ $item->jenis_pelanggaran }}</span></td>
                                    <td>{{ $item->hari_terlambat }} Hari</td>
                                    <td>Rp {{ number_format($item->denda_keterlambatan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->denda_kerusakan, 0, ',', '.') }}</td>
                                    <td><strong class="text-danger">Rp {{ number_format($item->denda_keterlambatan + $item->denda_kerusakan, 0, ',', '.') }}</strong></td>
                                    <td><span class="badge bg-warning text-dark">{{ $item->sanksi }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @elseif(request('jenis'))
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox display-4 d-block mb-2 text-muted opacity-50"></i>
                Tidak ada data laporan yang sesuai dengan filter tanggal yang dipilih.
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-filter-circle display-4 d-block mb-2 text-muted opacity-50"></i>
                Silakan pilih jenis laporan dan rentang tanggal di atas untuk menampilkan preview data.
            </div>
        @endif

    </div>
</div>

@endsection