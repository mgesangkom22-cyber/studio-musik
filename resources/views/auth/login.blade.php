<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Studio Musik UNU Yogyakarta</title>

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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
        }

        .login-wrapper {
            width: 100%;
            min-height: 100vh;
            display: flex;
        }

        /* Left Split Screen: Visual & Slogan */
        .login-left {
            flex: 1.1;
            background: linear-gradient(135deg, rgba(15, 109, 59, 0.92) 0%, rgba(8, 66, 35, 0.95) 100%),
                        url('{{ asset("images/studio-musik.png") }}') center/cover no-repeat;
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .login-left::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.2) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .left-logo {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }

        .slogan-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        /* Right Split Screen: Login Card */
        .login-right {
            flex: 0.9;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 40px 35px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-group-text {
            background-color: #f8faf9;
            border-end-0: none;
            color: #718096;
            border-radius: 12px 0 0 12px;
        }

        .form-control {
            background-color: #f8faf9;
            border-start-0: none;
            border-radius: 0 12px 12px 0;
            padding: 12px 15px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background-color: #ffffff;
            box-shadow: none;
            border-color: var(--unu-green);
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--unu-green);
            color: var(--unu-green);
            background-color: #ffffff;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--unu-green) 0%, var(--unu-green-dark) 100%);
            color: white;
            font-weight: 600;
            padding: 13px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(15, 109, 59, 0.3);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(15, 109, 59, 0.4);
            color: white;
        }

        /* Responsive Mobile */
        @media (max-width: 991.98px) {
            .login-left {
                display: none;
            }
            .login-right {
                flex: 1;
                background: linear-gradient(135deg, var(--unu-green) 0%, var(--unu-green-dark) 100%);
            }
            .login-card {
                box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <!-- Left Side: Visual Banner -->
        <div class="login-left">
            <div>
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="{{ asset('images/LOGO_UNU_YOGYAKARTA.png') }}" class="left-logo" alt="UNU Logo">
                    <div>
                        <h4 class="fw-bold m-0 text-white">STUDIO MUSIK</h4>
                        <span class="text-warning small font-monospace" style="letter-spacing: 1.5px;">UNU YOGYAKARTA</span>
                    </div>
                </div>
                <div class="slogan-badge mb-3">
                    <i class="bi bi-cpu-fill text-warning me-1"></i> RFID MONITORING SYSTEM
                </div>
            </div>

            <div class="my-auto py-5">
                <h1 class="display-5 fw-bold text-white mb-3">
                    Sistem Monitoring Peminjaman Alat Musik Berbasis RFID
                </h1>
                <p class="fs-5 text-white-50 leading-relaxed mb-0">
                    Selamat datang di Portal Administrator Studio Musik Universitas Nahdlatul Ulama Yogyakarta. Pantau ketersediaan instrumen, sanksi, dan riwayat peminjaman secara konsisten dan realtime.
                </p>
            </div>

            <div>
                <small class="text-white-50">&copy; 2026 Universitas Nahdlatul Ulama Yogyakarta. All rights reserved.</small>
            </div>
        </div>

        <!-- Right Side: Login Card Form -->
        <div class="login-right">
            <div class="login-card">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/LOGO_UNU_YOGYAKARTA.png') }}" style="width: 70px;" class="mb-3" alt="UNU Logo">
                    <h4 class="fw-bold text-dark mb-1">Login Admin</h4>
                    <p class="text-muted small">Silakan masuk menggunakan akun administrator Anda.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="/login" method="POST" id="form_login">
                    @csrf

                    <!-- Username Field -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Username Administrator</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username admin" required autofocus>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password" id="input_password" class="form-control" placeholder="Masukkan password admin" required>
                            <button type="button" class="btn btn-outline-secondary border-start-0" id="btn_toggle_password" style="border-radius: 0 12px 12px 0;">
                                <i class="bi bi-eye-fill" id="icon_password"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-login w-100 mb-3" id="btn_submit_login">
                        <span id="btn_text"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Dashboard</span>
                        <span id="btn_spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>

                    <div class="text-center">
                        <a href="{{ url('/') }}" class="text-decoration-none text-muted small">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Tampilan Publik
                        </a>
                    </div>
                </form>

                <div class="mt-4 pt-3 border-top text-center">
                    <small class="text-muted style-footer" style="font-size: 0.75rem;">
                        &copy; Universitas Nahdlatul Ulama Yogyakarta
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle Password Script & Spinner -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('btn_toggle_password')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('input_password');
            const icon = document.getElementById('icon_password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            }
        });

        document.getElementById('form_login')?.addEventListener('submit', function() {
            const btnText = document.getElementById('btn_text');
            const btnSpinner = document.getElementById('btn_spinner');
            if (btnText && btnSpinner) {
                btnText.classList.add('d-none');
                btnSpinner.classList.remove('d-none');
            }
        });
    </script>
</body>
</html>