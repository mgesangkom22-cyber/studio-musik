<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Anggota Studio Musik - UNU Yogyakarta</title>

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
            background-image: radial-gradient(rgba(15, 109, 59, 0.05) 1px, transparent 0);
            background-size: 24px 24px;
            color: #2c3e50;
            min-height: 100vh;
        }

        .reg-navbar {
            background: linear-gradient(135deg, var(--unu-green) 0%, var(--unu-green-dark) 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-reg {
            border: none;
            border-radius: 24px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
            background: #ffffff;
            overflow: hidden;
        }

        .reg-header-bg {
            background: linear-gradient(135deg, var(--unu-green) 0%, var(--unu-green-dark) 100%);
            color: white;
            padding: 35px 30px;
            position: relative;
        }

        .btn-gold {
            background: linear-gradient(135deg, #d4af37 0%, #b89320 100%);
            color: #000;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 14px 28px;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
            color: #000;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark reg-navbar py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/LOGO_UNU_YOGYAKARTA.png') }}" style="height: 42px;" class="me-2" alt="UNU Logo">
                <div>
                    <span class="fw-bold fs-6 d-block">STUDIO MUSIK UNU</span>
                    <small class="text-warning" style="font-size: 0.7rem; letter-spacing: 1px;">UNIVERSITAS NAHDLATUL ULAMA YOGYAKARTA</small>
                </div>
            </a>
            <div class="ms-auto">
                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Beranda Publik
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="card-reg">
                    <!-- Header Card Banner -->
                    <div class="reg-header-bg text-center">
                        <img src="{{ asset('images/LOGO_UNU_YOGYAKARTA.png') }}" style="height: 70px;" class="mb-3" alt="UNU Logo">
                        <h2 class="fw-bold mb-2">Pendaftaran Anggota Studio Musik</h2>
                        <p class="text-white-50 fs-6 max-w-2xl mx-auto mb-0 leading-relaxed">
                            Silakan lengkapi data diri Anda untuk menjadi anggota Studio Musik Universitas Nahdlatul Ulama Yogyakarta. Setelah data diverifikasi oleh admin, Anda akan mendapatkan kartu RFID yang digunakan dalam proses peminjaman alat musik.
                        </p>
                    </div>

                    <div class="card-body p-4 p-lg-5">

                        <!-- Success Alert Message State -->
                        @if(session('success'))
                            <div class="text-center py-4">
                                <div class="badge bg-success-subtle text-success p-3 rounded-circle mb-3" style="width: 80px; height: 80px; font-size: 2.5rem; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <h3 class="fw-bold text-success mb-2">✔️ Pendaftaran Berhasil!</h3>
                                <p class="text-muted lead max-w-lg mx-auto mb-4">
                                    Terima kasih telah melakukan pendaftaran. Silakan datang ke Studio Musik Universitas Nahdlatul Ulama Yogyakarta untuk proses verifikasi data dan pengambilan kartu RFID.
                                </p>
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="{{ url('/') }}" class="btn btn-gold px-4">
                                        <i class="bi bi-house-door-fill me-1"></i> Kembali ke Landing Page
                                    </a>
                                </div>
                            </div>
                        @else
                            @if(isset($errors) && $errors->any())
                                <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="/daftar" method="POST" enctype="multipart/form-data" id="form_pendaftaran">
                                @csrf

                                <!-- Nama Lengkap -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="nama" class="form-control" id="floatingNama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
                                    <label for="floatingNama"><i class="bi bi-person-fill me-2 text-success"></i> Nama Lengkap Mahasiswa</label>
                                </div>

                                <!-- NIM -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="nim" class="form-control" id="floatingNim" placeholder="NIM" value="{{ old('nim') }}" required>
                                    <label for="floatingNim"><i class="bi bi-card-text me-2 text-success"></i> Nomor Induk Mahasiswa (NIM)</label>
                                </div>

                                <!-- Program Studi -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="prodi" class="form-control" id="floatingProdi" placeholder="Program Studi" value="{{ old('prodi') }}" required>
                                    <label for="floatingProdi"><i class="bi bi-book-fill me-2 text-success"></i> Program Studi / Jurusan</label>
                                </div>

                                <!-- Email Official Student UNU -->
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Email Student UNU" value="{{ old('email') }}" required>
                                    <label for="floatingEmail"><i class="bi bi-envelope-fill me-2 text-success"></i> Email Official UNU (@student.unu-jogja.ac.id)</label>
                                </div>

                                <!-- No HP / WhatsApp -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="no_hp" class="form-control" id="floatingHp" placeholder="No HP" value="{{ old('no_hp') }}" required>
                                    <label for="floatingHp"><i class="bi bi-whatsapp me-2 text-success"></i> Nomor HP / WhatsApp Aktif</label>
                                </div>

                                <!-- Alamat -->
                                <div class="form-floating mb-4">
                                    <textarea name="alamat" class="form-control" id="floatingAlamat" placeholder="Alamat" style="height: 100px;" required>{{ old('alamat') }}</textarea>
                                    <label for="floatingAlamat"><i class="bi bi-geo-alt-fill me-2 text-success"></i> Alamat Domisili</label>
                                </div>

                                <!-- Upload Foto KTM -->
                                <div class="mb-4 p-3 bg-light rounded-3 border">
                                    <label class="form-label fw-bold text-dark mb-1"><i class="bi bi-camera-fill me-2 text-success"></i> Upload Foto Kartu Tanda Mahasiswa (KTM)</label>
                                    <input type="file" name="foto_ktm" class="form-control" accept="image/*" required>
                                    <small class="text-muted d-block mt-1">Pastikan foto KTM terlihat jelas dan tidak blur.</small>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-gold w-100 btn-lg" id="btn_submit_reg">
                                    <span id="reg_btn_text"><i class="bi bi-send-fill me-2"></i> Kirim Pendaftaran Anggota</span>
                                    <span id="reg_btn_spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                            </form>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script for Loading Spinner -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('form_pendaftaran')?.addEventListener('submit', function() {
            const btnText = document.getElementById('reg_btn_text');
            const btnSpinner = document.getElementById('reg_btn_spinner');
            if (btnText && btnSpinner) {
                btnText.classList.add('d-none');
                btnSpinner.classList.remove('d-none');
            }
        });
    </script>
</body>
</html>