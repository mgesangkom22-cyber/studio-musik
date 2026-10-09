@extends('layouts.app')

@section('title','Detail Peminjaman')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill mb-1">TRANSACTION DETAIL</span>
                    <h4 class="fw-bold text-dark m-0">
                        <i class="bi bi-file-earmark-text text-success me-2"></i> Detail Transaksi Peminjaman
                    </h4>
                </div>
                <div>
                    @if($peminjaman->status_pinjam === 'dipinjam')
                        <span class="badge bg-warning text-dark px-3 py-2 fs-6">🟡 Sedang Dipinjam</span>
                    @else
                        <span class="badge bg-success px-3 py-2 fs-6">🟢 Sudah Dikembalikan</span>
                    @endif
                </div>
            </div>

            <div class="card-body p-4">

                <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-person-badge me-2"></i> Data Anggota Peminjam</h5>
                <table class="table table-bordered align-middle mb-4">
                    <tr>
                        <th width="35%" class="bg-light text-muted">Nama Anggota</th>
                        <td class="fw-semibold text-dark">{{ $peminjaman->anggota->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-muted">NIM</th>
                        <td>{{ $peminjaman->anggota->nim ?? '-' }}</td>
                    </tr>
                </table>

                <h5 class="fw-bold mb-3 text-success">
                    <i class="bi bi-music-note-beamed me-2"></i> Daftar Alat Musik yang Dipinjam 
                    <span class="badge bg-success rounded-pill font-normal fs-6 ms-2">Kode Trx: {{ $peminjaman->kode_transaksi ?? ('TRX-' . $peminjaman->id_peminjaman) }}</span>
                </h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50" class="text-center">No</th>
                                <th width="70">Foto</th>
                                <th>Kode Alat</th>
                                <th>Nama Alat Musik</th>
                                <th>Kategori</th>
                                <th>Status Item</th>
                                <th class="text-center">Barcode Visual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($peminjaman->details && $peminjaman->details->count() > 0)
                                @foreach($peminjaman->details as $detail)
                                    @php
                                        $alatItem = $detail->alat;
                                        $bCode = $alatItem->barcode_alat ?? $alatItem->kode_alat ?? null;
                                        $fotoUrl = ($alatItem && $alatItem->foto_alat) ? asset('uploads/alat/' . $alatItem->foto_alat) : 'https://via.placeholder.com/60?text=Alat';
                                    @endphp
                                    <tr>
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $fotoUrl }}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td><strong class="font-monospace text-primary">{{ $alatItem->kode_alat ?? '-' }}</strong></td>
                                        <td><strong class="text-success">{{ $alatItem->nama_alat ?? '-' }}</strong></td>
                                        <td><span class="badge bg-info text-dark">{{ $alatItem->kategori ?? '-' }}</span></td>
                                        <td>
                                            @if($detail->status_detail === 'dikembalikan')
                                                <span class="badge bg-success">🟢 Dikembalikan</span>
                                            @else
                                                <span class="badge bg-warning text-dark">🟡 Dipinjam</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-2">
                                            @if($bCode)
                                                <div class="d-inline-block p-1 bg-light border rounded">
                                                    {!! DNS1D::getBarcodeSVG($bCode, 'C128', 1.4, 40, 'black', false) !!}
                                                </div>
                                                <small class="d-block mt-1 fw-bold text-dark font-monospace" style="font-size: 0.75rem;">{{ $bCode }}</small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $alatItem = $peminjaman->alat;
                                    $bCode = $alatItem->barcode_alat ?? $alatItem->kode_alat ?? null;
                                    $fotoUrl = ($alatItem && $alatItem->foto_alat) ? asset('uploads/alat/' . $alatItem->foto_alat) : 'https://via.placeholder.com/60?text=Alat';
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold">1</td>
                                    <td>
                                        <img src="{{ $fotoUrl }}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td><strong class="font-monospace text-primary">{{ $alatItem->kode_alat ?? '-' }}</strong></td>
                                    <td><strong class="text-success">{{ $alatItem->nama_alat ?? '-' }}</strong></td>
                                    <td><span class="badge bg-info text-dark">{{ $alatItem->kategori ?? '-' }}</span></td>
                                    <td><span class="badge bg-warning text-dark">🟡 Dipinjam</span></td>
                                    <td class="text-center py-2">
                                        @if($bCode)
                                            <div class="d-inline-block p-1 bg-light border rounded">
                                                {!! DNS1D::getBarcodeSVG($bCode, 'C128', 1.4, 40, 'black', false) !!}
                                            </div>
                                            <small class="d-block mt-1 fw-bold text-dark font-monospace" style="font-size: 0.75rem;">{{ $bCode }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <h5 class="fw-bold mb-3 text-info"><i class="bi bi-calendar-event me-2"></i> Jadwal Transaksi</h5>
                <table class="table table-bordered align-middle mb-4">
                    <tr>
                        <th width="35%" class="bg-light text-muted">Tanggal Pinjam</th>
                        <td><i class="bi bi-calendar-check text-success me-1"></i> {{ $peminjaman->tanggal_pinjam }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-muted">Batas Pengembalian</th>
                        <td><i class="bi bi-clock-history text-danger me-1"></i> <strong class="text-danger">{{ $peminjaman->batas_kembali }}</strong></td>
                    </tr>
                </table>

                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                    @if($peminjaman->status_pinjam === 'dipinjam')
                        <a href="{{ route('admin.pengembalian.show', $peminjaman->id_peminjaman) }}" class="btn btn-success btn-lg px-4">
                            <i class="bi bi-arrow-return-left me-1"></i> Proses Pengembalian Alat
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@endsection