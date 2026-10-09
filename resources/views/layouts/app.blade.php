<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Studio Musik UNU Yogyakarta</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Chart.js for Dashboard Monitoring -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --unu-green: #0f6d3b;
            --unu-green-dark: #094726;
            --unu-green-light: #168a4d;
            --unu-gold: #d4af37;
            --unu-gold-light: #f5d77f;
            --unu-bg: #f4f7f6;
            --sidebar-width: 270px;
            --sidebar-collapsed-width: 80px;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--unu-bg);
            color: #2c3e50;
            overflow-x: hidden;
        }

        /* Top Navbar */
        .top-navbar {
            height: 70px;
            background: linear-gradient(135deg, var(--unu-green) 0%, var(--unu-green-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand-logo {
            width: 45px;
            height: 45px;
            object-fit: contain;
        }

        .live-clock-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 6px 16px;
            font-size: 0.88rem;
            font-weight: 500;
        }

        /* Sidebar Styling */
        .app-wrapper {
            display: flex;
            min-height: calc(100vh - 70px);
        }

        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            z-index: 1020;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .sidebar-header-title {
            display: none;
        }

        .sidebar.collapsed .sidebar-link {
            text-align: center;
            padding: 16px 0;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-link i {
            margin-right: 0 !important;
            font-size: 1.4rem;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            text-align: center;
        }

        .sidebar-menu {
            padding: 15px 12px;
            flex: 1;
        }

        .sidebar-category {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #a0aec0;
            letter-spacing: 1px;
            margin: 15px 12px 8px 12px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 18px;
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            border-radius: 12px;
            margin-bottom: 6px;
            transition: all 0.25s ease;
        }

        .sidebar-link i {
            font-size: 1.25rem;
            margin-right: 14px;
            transition: transform 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(15, 109, 59, 0.08);
            color: var(--unu-green);
            transform: translateX(4px);
        }

        .sidebar-link:hover i {
            transform: scale(1.15);
        }

        .sidebar-link.active-menu {
            background: linear-gradient(135deg, var(--unu-green) 0%, var(--unu-green-light) 100%);
            color: white !important;
            box-shadow: 0 4px 12px rgba(15, 109, 59, 0.3);
            font-weight: 600;
        }

        .sidebar-link.active-menu i {
            color: var(--unu-gold-light);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            transition: all 0.3s ease;
            max-width: 100%;
        }

        /* Global Cards & Components */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .badge {
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 30px;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 8px 18px;
            transition: all 0.2s ease;
        }

        .btn-success {
            background-color: var(--unu-green);
            border-color: var(--unu-green);
        }

        .btn-success:hover {
            background-color: var(--unu-green-dark);
            border-color: var(--unu-green-dark);
            transform: translateY(-1px);
        }

        .btn-gold {
            background-color: var(--unu-gold);
            color: #000;
            border-color: var(--unu-gold);
            font-weight: 600;
        }

        .btn-gold:hover {
            background-color: #c59b27;
            color: #000;
        }

        .footer {
            text-align: center;
            padding: 25px 0 10px 0;
            color: #a0aec0;
            font-size: 0.85rem;
        }

        /* Responsive Mobile */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -270px;
                top: 70px;
                bottom: 0;
                height: calc(100vh - 70px);
            }
            .sidebar.mobile-show {
                left: 0;
            }
            .main-content {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar top-navbar">
        <div class="container-fluid px-3 px-lg-4">
            <div class="d-flex align-items-center">
                <button type="button" id="btn_toggle_sidebar" class="btn text-white p-1 me-3 border-0 fs-4">
                    <i class="bi bi-list"></i>
                </button>

                <a class="navbar-brand d-flex align-items-center text-white m-0" href="{{ url('/dashboard') }}">
                    <img src="{{ asset('images/LOGO_UNU_YOGYAKARTA.png') }}" class="navbar-brand-logo me-2" alt="UNU Logo">
                    <div class="lh-1">
                        <span class="fw-bold fs-6 d-block">STUDIO MUSIK</span>
                        <small class="text-white-50" style="font-size: 0.72rem; letter-spacing: 1px;">UNU YOGYAKARTA</small>
                    </div>
                </a>
            </div>

            <!-- Navbar Center: Live Clock -->
            <div class="d-none d-md-block">
                <div class="live-clock-badge text-white">
                    <i class="bi bi-clock me-2 text-warning"></i>
                    <span id="live_date">-- -- ----</span> | <strong id="live_clock">00:00:00 WIB</strong>
                </div>
            </div>

            <!-- Navbar Right: Public View & Profile Dropdown -->
            <div class="d-flex align-items-center">
                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-light rounded-pill me-3 d-none d-sm-inline-flex align-items-center" target="_blank" title="Buka Dashboard Publik">
                    <i class="bi bi-globe me-1"></i> Monitoring Publik
                </a>

                @php
                    $notifPending = \App\Models\PendaftaranAnggota::where('status_verifikasi', 'menunggu')->latest()->get();
                    $notifCount = $notifPending->count();
                @endphp

                <!-- Notification Bell Dropdown -->
                <div class="dropdown me-3">
                    <button class="btn btn-link text-white position-relative p-2 border-0 shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Pendaftaran Baru Menunggu Verifikasi">
                        <i class="bi bi-bell-fill fs-5"></i>
                        @if($notifCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm" style="font-size: 0.72rem; padding: 4px 7px;">
                                {{ $notifCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0" style="border-radius: 16px; width: 340px; overflow: hidden;">
                        <div class="bg-success text-white px-3 py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold m-0"><i class="bi bi-bell me-2"></i> Pendaftaran Baru</h6>
                            <span class="badge bg-white text-success rounded-pill fw-bold">{{ $notifCount }} Menunggu</span>
                        </div>
                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse($notifPending as $pendaftar)
                                <a href="{{ route('admin.pendaftaran.show', $pendaftar->id_pendaftaran) }}" class="dropdown-item px-3 py-2 border-bottom text-wrap">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-success-subtle text-success rounded-circle p-2 me-2 mt-1 flex-shrink-0" style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-person-fill fs-6"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong class="d-block text-dark lh-sm" style="font-size: 0.88rem;">{{ $pendaftar->nama }}</strong>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">NIM: {{ $pendaftar->nim }} &bull; {{ $pendaftar->prodi }}</small>
                                            <small class="text-success fw-semibold" style="font-size: 0.72rem;">
                                                <i class="bi bi-clock me-1"></i> {{ $pendaftar->created_at ? $pendaftar->created_at->diffForHumans() : 'Baru saja' }}
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle fs-2 text-success opacity-50 d-block mb-1"></i>
                                    <small>Tidak ada pendaftaran baru yang menunggu verifikasi.</small>
                                </div>
                            @endforelse
                        </div>
                        <div class="bg-light p-2 text-center border-top">
                            <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-sm btn-link text-success fw-bold text-decoration-none py-1">
                                Lihat Semua Pendaftaran <i class="bi bi-arrow-right me-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn text-white dropdown-toggle d-flex align-items-center p-1 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-warning text-dark rounded-circle me-2 fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.95rem; border: 2px solid var(--unu-gold);">
                            {{ strtoupper(substr(Session::get('nama_admin') ?? 'A', 0, 2)) }}
                        </div>
                        <div class="text-start d-none d-md-block me-1">
                            <span class="fw-semibold d-block lh-1" style="font-size: 0.9rem;">{{ Session::get('nama_admin') ?? 'Admin Studio' }}</span>
                            <small class="text-white-50" style="font-size: 0.75rem;">Administrator</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 14px; min-width: 220px;">
                        <li class="px-3 py-2 border-bottom">
                            <span class="d-block fw-bold text-dark">{{ Session::get('nama_admin') ?? 'Admin Studio' }}</span>
                            <small class="text-muted">Administrator System</small>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ url('/dashboard') }}">
                                <i class="bi bi-speedometer2 me-2 text-success"></i> Dashboard Monitoring
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ url('/') }}" target="_blank">
                                <i class="bi bi-display me-2 text-info"></i> Tampilan Publik
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- App Wrapper (Sidebar + Main Content) -->
    <div class="app-wrapper">

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="sidebar-menu">

                <div class="sidebar-category">NAVIGASI UTAMA</div>

                <a href="/dashboard" class="sidebar-link {{ request()->is('dashboard') ? 'active-menu' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="sidebar-text">Dashboard Monitoring</span>
                </a>

                <div class="sidebar-category">MANAJEMEN ANGGOTA</div>

                <a href="/pendaftaran-anggota" class="sidebar-link {{ request()->is('pendaftaran-anggota*') ? 'active-menu' : '' }}">
                    <i class="bi bi-person-plus-fill"></i>
                    <span class="sidebar-text">Pendaftaran Anggota</span>
                </a>

                <a href="/anggota" class="sidebar-link {{ request()->is('anggota*') ? 'active-menu' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span class="sidebar-text">Data Anggota</span>
                </a>

                <div class="sidebar-category">INVENTARIS & TRANSAKSI</div>

                <a href="/alat" class="sidebar-link {{ request()->is('alat*') ? 'active-menu' : '' }}">
                    <i class="bi bi-music-note-beamed"></i>
                    <span class="sidebar-text">Data Alat Musik</span>
                </a>

                <a href="/peminjaman" class="sidebar-link {{ request()->is('peminjaman*') ? 'active-menu' : '' }}">
                    <i class="bi bi-box-arrow-in-down"></i>
                    <span class="sidebar-text">Peminjaman Alat</span>
                </a>

                <a href="/pengembalian" class="sidebar-link {{ request()->is('pengembalian*') ? 'active-menu' : '' }}">
                    <i class="bi bi-box-arrow-up"></i>
                    <span class="sidebar-text">Pengembalian Alat</span>
                </a>

                <a href="{{ route('admin.denda.index') }}" class="sidebar-link {{ request()->is('denda*') ? 'active-menu' : '' }}">
                    <i class="bi bi-cash-stack text-danger"></i>
                    <span class="sidebar-text">Pembayaran Denda</span>
                </a>

                <div class="sidebar-category">REKAP & LAPORAN</div>

                <a href="{{ route('admin.catatan-pelanggaran.index') }}" class="sidebar-link {{ request()->is('catatan-pelanggaran*') ? 'active-menu' : '' }}">
                    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                    <span class="sidebar-text">Pelanggaran & Sanksi</span>
                </a>

                <a href="/laporan" class="sidebar-link {{ request()->is('laporan*') ? 'active-menu' : '' }}">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span class="sidebar-text">Laporan Inventory</span>
                </a>

                <hr class="my-3 mx-2 text-muted opacity-25">

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link text-danger border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="sidebar-text">Keluar / Logout</span>
                    </button>
                </form>

            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')

            <footer class="footer">
                <div>
                    <strong>Sistem Monitoring Peminjaman Alat Musik Berbasis RFID</strong> &copy; 2026
                </div>
                <small class="text-muted">Universitas Nahdlatul Ulama Yogyakarta</small>
            </footer>

        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle Handler
        document.getElementById('btn_toggle_sidebar')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 992) {
                sidebar.classList.toggle('mobile-show');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });

        // Realtime Clock Handler
        function updateLiveClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const dayName = days[now.getDay()];
            const day = String(now.getDate()).padStart(2, '0');
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const dateStr = `${dayName}, ${day} ${monthName} ${year}`;
            const timeStr = `${hours}:${minutes}:${seconds} WIB`;

            const dateEl = document.getElementById('live_date');
            const clockEl = document.getElementById('live_clock');

            if (dateEl) dateEl.textContent = dateStr;
            if (clockEl) clockEl.textContent = timeStr;
        }

        setInterval(updateLiveClock, 1000);
        updateLiveClock();
    </script>
</body>
</html>