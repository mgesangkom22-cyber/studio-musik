<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Alat Musik - Studio Musik UNU Yogyakarta</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --unu-green: #0f6d3b;
            --unu-green-dark: #084223;
            --unu-gold: #d4af37;
            --unu-gold-light: #f5d77f;
            --unu-bg: #f4f7f6;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--unu-bg);
            color: #2c3e50;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Navbar */
        .public-navbar {
            background: linear-gradient(135deg, var(--unu-green) 0%, var(--unu-green-dark) 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--unu-green) 0%, #07351c 100%);
            color: white;
            padding: 80px 0 60px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .btn-gold {
            background: linear-gradient(135deg, #d4af37 0%, #b89320 100%);
            color: #000;
            font-weight: 600;
            border: none;
            border-radius: 30px;
            padding: 12px 28px;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
            color: #000;
        }

        .btn-outline-gold {
            border: 2px solid var(--unu-gold);
            color: var(--unu-gold-light);
            font-weight: 600;
            border-radius: 30px;
            padding: 10px 26px;
            transition: all 0.3s ease;
        }

        .btn-outline-gold:hover {
            background: var(--unu-gold);
            color: #000;
        }

        /* Monitoring Summary Banner */
        .summary-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            margin-top: -40px;
            z-index: 10;
            position: relative;
        }

        .summary-item {
            padding: 20px;
            border-right: 1px solid #f0f0f0;
            text-align: center;
        }

        .summary-item:last-child {
            border-right: none;
        }

        /* Tool Cards */
        .tool-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .tool-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        .tool-img-wrapper {
            height: 200px;
            position: relative;
            background: #f8faf9;
            overflow: hidden;
        }

        .tool-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .tool-card:hover .tool-img {
            transform: scale(1.06);
        }

        .badge-status {
            position: absolute;
            top: 12px;
            right: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

    @php
        use App\Models\AlatMusik;
        use App\Models\Anggota;

        $allAlat = AlatMusik::orderBy('nama_alat')->get();
        $totalAlat = $allAlat->count();
        $tersediaCount = $allAlat->where('status_alat', 'tersedia')->count();
        $dipinjamCount = $allAlat->where('status_alat', 'dipinjam')->count();
        $rusakCount = $allAlat->where('status_alat', 'rusak')->count();
        $maintenanceCount = $allAlat->where('status_alat', 'maintenance')->count();
        $totalAnggota = Anggota::count();
    @endphp

    <!-- Public Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark public-navbar py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/LOGO_UNU_YOGYAKARTA.png') }}" class="navbar-logo me-2" alt="UNU Logo">
                <div>
                    <span class="fw-bold fs-5 d-block">STUDIO MUSIK UNU</span>
                    <small class="text-warning" style="font-size: 0.72rem; letter-spacing: 1px;">UNIVERSITAS NAHDLATUL ULAMA YOGYAKARTA</small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#publicMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="publicMenu">
                <ul class="navbar-nav align-items-center gap-2 mt-3 mt-lg-0">
                    <li class="nav-item">
                        <a href="#monitoring" class="nav-link text-white fw-medium"><i class="bi bi-broadcast me-1"></i> Monitoring Live</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/daftar') }}" class="btn btn-outline-gold btn-sm px-3 ms-lg-2">
                            <i class="bi bi-person-plus-fill me-1"></i> Daftar Anggota
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/login') }}" class="btn btn-gold btn-sm px-4">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center text-lg-start">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-3">
                        <i class="bi bi-cpu-fill me-1"></i> SISTEM MONITORING RFID REALTIME
                    </div>
                    <h1 class="display-4 fw-bold mb-3 leading-tight">
                        Monitoring Alat Musik Studio Musik <span class="text-warning">UNU Yogyakarta</span>
                    </h1>
                    <p class="lead mb-4 text-white-50">
                        Cek ketersediaan instrumen & peralatan studio secara realtime dari mana saja sebelum Anda datang ke Studio Musik Universitas Nahdlatul Ulama Yogyakarta.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="#monitoring" class="btn btn-gold btn-lg">
                            <i class="bi bi-music-note-list me-1"></i> Lihat Status Alat Musik
                        </a>
                        <a href="{{ url('/daftar') }}" class="btn btn-outline-gold btn-lg">
                            <i class="bi bi-person-badge me-1"></i> Pendaftaran Anggota
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="{{ asset('images/studio-musik.png') }}" class="img-fluid rounded-4 shadow-lg" style="max-height: 380px; object-fit: cover;" alt="Studio Musik">
                </div>
            </div>
        </div>
    </section>

    <!-- Realtime Summary Counter Banner -->
    <div class="container">
        <div class="summary-card">
            <div class="row g-0">
                <div class="col-md-2 col-6 summary-item">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Alat</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalAlat }}</h3>
                </div>
                <div class="col-md-2 col-6 summary-item">
                    <span class="text-success small fw-bold text-uppercase d-block mb-1">🟢 Tersedia</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $tersediaCount }}</h3>
                </div>
                <div class="col-md-2 col-6 summary-item">
                    <span class="text-warning small fw-bold text-uppercase d-block mb-1">🟡 Dipinjam</span>
                    <h3 class="fw-bold mb-0 text-warning">{{ $dipinjamCount }}</h3>
                </div>
                <div class="col-md-2 col-6 summary-item">
                    <span class="text-danger small fw-bold text-uppercase d-block mb-1">🔴 Rusak</span>
                    <h3 class="fw-bold mb-0 text-danger">{{ $rusakCount }}</h3>
                </div>
                <div class="col-md-2 col-6 summary-item">
                    <span class="text-secondary small fw-bold text-uppercase d-block mb-1">⚙️ Maintenance</span>
                    <h3 class="fw-bold mb-0 text-secondary">{{ $maintenanceCount }}</h3>
                </div>
                <div class="col-md-2 col-12 summary-item">
                    <span class="text-primary small fw-bold text-uppercase d-block mb-1">👥 Anggota</span>
                    <h3 class="fw-bold mb-0 text-primary">{{ $totalAnggota }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Monitoring Section -->
    <section id="monitoring" class="py-5">
        <div class="container py-4">

            <div class="text-center mb-5">
                <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill mb-2">LIVE INVENTORY MONITORING</span>
                <h2 class="fw-bold text-dark display-6">Status Peralatan Studio Musik</h2>
                <p class="text-muted">Informasi status ketersediaan instrumen terhubung langsung dengan database realtime.</p>
            </div>

            <!-- Filter & Search Controls -->
            <div class="card border-0 shadow-sm mb-5 p-3" style="border-radius: 16px;">
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="public_search" class="form-control border-start-0 ps-0" placeholder="Cari nama atau kode alat musik...">
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <select id="public_filter_kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="Gitar">Gitar</option>
                            <option value="Bass">Bass</option>
                            <option value="Keyboard">Keyboard</option>
                            <option value="Drum">Drum</option>
                            <option value="Mikrofon">Mikrofon</option>
                            <option value="Amplifier">Amplifier</option>
                            <option value="Sound System">Sound System</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <select id="public_filter_status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="tersedia">🟢 Tersedia</option>
                            <option value="dipinjam">🟡 Sedang Dipinjam</option>
                            <option value="rusak">🔴 Rusak</option>
                            <option value="maintenance">⚙️ Maintenance</option>
                        </select>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" id="btn_reset_filter" class="btn btn-light w-100" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></button>
                    </div>
                </div>
            </div>

            <!-- Instruments Card Grid -->
            <div class="row g-4" id="tool_cards_container">
                @foreach($allAlat as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 tool-card-item"
                         data-name="{{ strtolower($item->nama_alat) }}"
                         data-kode="{{ strtolower($item->kode_alat) }}"
                         data-kategori="{{ $item->kategori }}"
                         data-status="{{ strtolower($item->status_alat) }}">
                        
                        <div class="tool-card">
                            <div class="tool-img-wrapper">
                                @if($item->foto_alat)
                                    <img src="{{ asset('uploads/alat/'.rawurlencode($item->foto_alat)) }}" class="tool-img" alt="{{ $item->nama_alat }}" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=500&auto=format&fit=crop';">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                        <i class="bi bi-music-note-beamed display-3"></i>
                                    </div>
                                @endif

                                @if($item->status_alat === 'tersedia')
                                    <span class="badge bg-success badge-status">🟢 Tersedia</span>
                                @elseif($item->status_alat === 'dipinjam')
                                    <span class="badge bg-warning text-dark badge-status">🟡 Dipinjam</span>
                                @elseif($item->status_alat === 'rusak')
                                    <span class="badge bg-danger badge-status">🔴 Rusak</span>
                                @else
                                    <span class="badge bg-secondary badge-status">⚙️ Maintenance</span>
                                @endif
                            </div>

                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark me-1">{{ $item->kategori }}</span>
                                    <span class="badge bg-secondary-subtle text-secondary">{{ $item->kode_alat }}</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-2">{{ $item->nama_alat }}</h5>
                                <div class="text-muted small mb-3">
                                    Maksimal Pinjam: <strong>{{ $item->maks_lama_pinjam }} Hari</strong>
                                </div>

                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <small class="text-muted font-monospace"><i class="bi bi-barcode me-1"></i> {{ $item->barcode_alat ?? $item->kode_alat }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty Filter Warning -->
            <div id="no_tools_found" class="text-center py-5 d-none">
                <i class="bi bi-search display-3 text-muted"></i>
                <h4 class="fw-bold mt-3 text-muted">Alat Musik Tidak Ditemukan</h4>
                <p class="text-muted">Coba ubah kata kunci pencarian atau reset filter kategori.</p>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <div class="d-flex justify-content-center align-items-center mb-2">
                <img src="{{ asset('images/LOGO_UNU_YOGYAKARTA.png') }}" style="height: 35px;" class="me-2" alt="UNU Logo">
                <span class="fw-bold fs-6">Studio Musik Universitas Nahdlatul Ulama Yogyakarta</span>
            </div>
            <small class="text-white-50">&copy; 2026 Sistem Monitoring Peminjaman Alat Musik Berbasis RFID. All rights reserved.</small>
        </div>
    </footer>

    <!-- Filter JS Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('public_search');
            const filterKategori = document.getElementById('public_filter_kategori');
            const filterStatus = document.getElementById('public_filter_status');
            const btnReset = document.getElementById('btn_reset_filter');
            const cardItems = document.querySelectorAll('.tool-card-item');
            const noFound = document.getElementById('no_tools_found');

            function applyFilters() {
                const searchVal = searchInput.value.toLowerCase().trim();
                const kategoriVal = filterKategori.value;
                const statusVal = filterStatus.value.toLowerCase();

                let visibleCount = 0;

                cardItems.forEach(item => {
                    const name = item.getAttribute('data-name');
                    const kode = item.getAttribute('data-kode');
                    const kategori = item.getAttribute('data-kategori');
                    const status = item.getAttribute('data-status');

                    const matchesSearch = !searchVal || name.includes(searchVal) || kode.includes(searchVal);
                    const matchesKategori = !kategoriVal || kategori === kategoriVal;
                    const matchesStatus = !statusVal || status === statusVal;

                    if (matchesSearch && matchesKategori && matchesStatus) {
                        item.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        item.classList.add('d-none');
                    }
                });

                if (visibleCount === 0) {
                    noFound.classList.remove('d-none');
                } else {
                    noFound.classList.add('d-none');
                }
            }

            searchInput.addEventListener('input', applyFilters);
            filterKategori.addEventListener('change', applyFilters);
            filterStatus.addEventListener('change', applyFilters);

            btnReset.addEventListener('click', function() {
                searchInput.value = '';
                filterKategori.value = '';
                filterStatus.value = '';
                applyFilters();
            });
        });
    </script>
</body>
</html>