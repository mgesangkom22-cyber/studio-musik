@extends('layouts.app')

@section('title','Dashboard Monitoring')

@section('content')

<!-- Welcome Hero Banner -->
<div class="card shadow-sm border-0 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f6d3b 0%, #084223 100%); border-radius: 20px;">
    <div class="card-body p-4 p-lg-5 text-white position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="badge bg-warning text-dark fw-bold mb-2 px-3 py-2 rounded-pill">
                    <i class="bi bi-broadcast me-1"></i> MONITORING REALTIME SYSTEM
                </div>
                <h2 class="fw-bold display-6 mb-2">
                    Selamat Datang, {{ Session::get('nama_admin') ?? 'Administrator' }} 👋
                </h2>
                <p class="mb-0 text-white-50 leading-relaxed fs-6">
                    Sistem Monitoring Peminjaman Alat Musik Berbasis RFID di Studio Musik Universitas Nahdlatul Ulama Yogyakarta. Pantau status peralatan, transaksi, dan anggota secara konsisten.
                </p>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <img src="{{ asset('images/LOGO_UNU_YOGYAKARTA.png') }}" alt="UNU Logo" style="max-height: 120px; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3));">
            </div>
        </div>
    </div>
</div>

<!-- 9 Monitoring Stats Cards Grid -->
<div class="row g-3 mb-4">
    <!-- Total Anggota -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover border-0 shadow-sm h-100" style="border-left: 5px solid #0f6d3b !important; border-radius: 16px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-circle p-3 me-3 text-white d-flex align-items-center justify-content-center" style="background: rgba(15, 109, 59, 0.15); width: 55px; height: 55px;">
                    <i class="bi bi-people-fill fs-3 text-success"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Total Anggota</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalAnggota }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Alat -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover border-0 shadow-sm h-100" style="border-left: 5px solid #0284c7 !important; border-radius: 16px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-circle p-3 me-3 text-white d-flex align-items-center justify-content-center" style="background: rgba(2, 132, 199, 0.15); width: 55px; height: 55px;">
                    <i class="bi bi-music-note-beamed fs-3 text-info"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Total Alat Musik</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalAlat }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Alat Tersedia -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover border-0 shadow-sm h-100" style="border-left: 5px solid #10b981 !important; border-radius: 16px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-circle p-3 me-3 text-white d-flex align-items-center justify-content-center" style="background: rgba(16, 185, 129, 0.15); width: 55px; height: 55px;">
                    <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Alat Tersedia</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $alatTersedia }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Alat Dipinjam -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover border-0 shadow-sm h-100" style="border-left: 5px solid #f59e0b !important; border-radius: 16px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-circle p-3 me-3 text-white d-flex align-items-center justify-content-center" style="background: rgba(245, 158, 11, 0.15); width: 55px; height: 55px;">
                    <i class="bi bi-arrow-repeat fs-3 text-warning"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Sedang Dipinjam</span>
                    <h3 class="fw-bold mb-0 text-warning">{{ $alatDipinjam }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Alat Rusak -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover border-0 shadow-sm h-100" style="border-left: 5px solid #ef4444 !important; border-radius: 16px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-circle p-3 me-3 text-white d-flex align-items-center justify-content-center" style="background: rgba(239, 68, 68, 0.15); width: 55px; height: 55px;">
                    <i class="bi bi-exclamation-octagon-fill fs-3 text-danger"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Alat Rusak</span>
                    <h3 class="fw-bold mb-0 text-danger">{{ $alatRusak }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Alat Maintenance -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover border-0 shadow-sm h-100" style="border-left: 5px solid #6b7280 !important; border-radius: 16px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-circle p-3 me-3 text-white d-flex align-items-center justify-content-center" style="background: rgba(107, 114, 128, 0.15); width: 55px; height: 55px;">
                    <i class="bi bi-tools fs-3 text-secondary"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Maintenance</span>
                    <h3 class="fw-bold mb-0 text-secondary">{{ $alatMaintenance }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Anggota Ditangguhkan / Sanksi -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover border-0 shadow-sm h-100" style="border-left: 5px solid #8b5cf6 !important; border-radius: 16px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-circle p-3 me-3 text-white d-flex align-items-center justify-content-center" style="background: rgba(139, 92, 246, 0.15); width: 55px; height: 55px;">
                    <i class="bi bi-person-x-fill fs-3 text-purple" style="color:#8b5cf6;"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Anggota Sanksi</span>
                    <h3 class="fw-bold mb-0" style="color:#8b5cf6;">{{ $anggotaSanksi }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Hari Ini -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-hover border-0 shadow-sm h-100" style="border-left: 5px solid #14b8a6 !important; border-radius: 16px;">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-circle p-3 me-3 text-white d-flex align-items-center justify-content-center" style="background: rgba(20, 184, 166, 0.15); width: 55px; height: 55px;">
                    <i class="bi bi-journal-check fs-3" style="color:#14b8a6;"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Pinjam Hari Ini</span>
                    <h3 class="fw-bold mb-0" style="color:#14b8a6;">{{ $transaksiHariIni }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Monitoring Charts -->
<div class="row g-4 mb-4">
    <!-- Chart 1: Distribusi Status Alat -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 18px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="bi bi-pie-chart-fill text-success me-2"></i> Distribusi Status Alat
                    </h5>
                    <span class="badge bg-light text-dark">Realtime</span>
                </div>
                <div style="height: 260px; position: relative;">
                    <canvas id="chartStatusAlat"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 2: Kategori Alat Musik -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100 p-3" style="border-radius: 18px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="bi bi-bar-chart-line-fill text-primary me-2"></i> Jumlah Alat Per Kategori
                    </h5>
                    <span class="badge bg-light text-dark">Inventory</span>
                </div>
                <div style="height: 260px; position: relative;">
                    <canvas id="chartKategoriAlat"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Tables: Top 5 Borrowed Tools & Recent Violations -->
<div class="row g-4 mb-4">
    <!-- Top 5 Alat Paling Sering Dipinjam -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0 text-dark">
                    <i class="bi bi-trophy-fill text-warning me-2"></i> 5 Alat Paling Sering Dipinjam
                </h5>
                <a href="{{ url('/alat') }}" class="btn btn-sm btn-light text-success fw-semibold">Lihat Semua</a>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Foto</th>
                                <th>Nama Alat</th>
                                <th>Kategori</th>
                                <th class="text-center">Dipinjam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topAlat as $alat)
                                <tr>
                                    <td>
                                        @if($alat->foto_alat)
                                            <img src="{{ asset('uploads/alat/'.$alat->foto_alat) }}" class="rounded-3" style="width: 45px; height: 45px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 45px; height: 45px;">
                                                <i class="bi bi-music-note"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="d-block text-dark">{{ $alat->nama_alat }}</strong>
                                        <small class="text-muted">{{ $alat->kode_alat }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ $alat->kategori }}</span></td>
                                    <td class="text-center"><span class="badge bg-primary rounded-pill">{{ $alat->peminjaman_count }} x</span></td>
                                    <td>
                                        @if($alat->status_alat === 'tersedia')
                                            <span class="badge bg-success">Tersedia</span>
                                        @elseif($alat->status_alat === 'dipinjam')
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($alat->status_alat) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada data peminjaman alat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pelanggaran Terbaru -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0 text-dark">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Pelanggaran Terbaru
                </h5>
                <a href="{{ route('admin.catatan-pelanggaran.index') }}" class="btn btn-sm btn-light text-danger fw-semibold">Lihat Semua</a>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Anggota</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Tanggal</th>
                                <th>Sanksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggaranTerbaru as $pelanggaran)
                                <tr>
                                    <td>
                                        <strong class="d-block text-dark">{{ $pelanggaran->anggota->nama ?? '-' }}</strong>
                                        <small class="text-muted">NIM: {{ $pelanggaran->anggota->nim ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $pelanggaran->jenis_pelanggaran }}</span>
                                    </td>
                                    <td><small class="text-muted">{{ $pelanggaran->tanggal_pengembalian }}</small></td>
                                    <td>
                                        <span class="badge bg-warning text-dark">{{ $pelanggaran->sanksi ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada catatan pelanggaran terbaru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart JS Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Chart Status Alat (Doughnut)
    const ctxStatus = document.getElementById('chartStatusAlat')?.getContext('2d');
    if (ctxStatus) {
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Tersedia', 'Sedang Dipinjam', 'Rusak', 'Maintenance'],
                datasets: [{
                    data: [{{ $alatTersedia }}, {{ $alatDipinjam }}, {{ $alatRusak }}, {{ $alatMaintenance }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#6b7280'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // 2. Chart Kategori Alat (Bar)
    const ctxKategori = document.getElementById('chartKategoriAlat')?.getContext('2d');
    if (ctxKategori) {
        const labelsKategori = {!! json_encode($kategoriStats->keys()) !!};
        const dataKategori = {!! json_encode($kategoriStats->values()) !!};

        new Chart(ctxKategori, {
            type: 'bar',
            data: {
                labels: labelsKategori,
                datasets: [{
                    label: 'Jumlah Unit',
                    data: dataKategori,
                    backgroundColor: '#0f6d3b',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }
});
</script>

@endsection