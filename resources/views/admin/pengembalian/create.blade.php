@extends('layouts.app')

@section('title','Pengembalian Alat Musik')

@section('content')

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<div class="row">
    <div class="col-md-9 mx-auto">
        <div class="card shadow border-0" style="border-radius: 20px;">
            <div class="card-header bg-primary text-white py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-box-arrow-up-right me-2"></i> Pengembalian Alat Musik (RFID & Barcode)
                    </h4>
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                        <i class="bi bi-broadcast text-primary me-1"></i> RFID RC522 & Barcode
                    </span>
                </div>
            </div>

            <div class="card-body p-4">

                <!-- SECTION 1: SCAN RFID ANGGOTA -->
                <div class="card bg-light border-primary mb-4" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <label class="form-label fw-bold text-primary fs-5 mb-2">
                            <i class="bi bi-person-badge-fill me-2"></i> 1. Scan RFID Anggota Peminjam
                        </label>
                        <p class="text-muted small mb-3">
                            Tempelkan kartu RFID anggota pada sensor ESP32 untuk memuat seluruh alat yang sedang dipinjam:
                        </p>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white text-primary border-primary">
                                <i class="bi bi-broadcast fs-4"></i>
                            </span>
                            <input type="text"
                                   id="rfid_pengembalian_input"
                                   class="form-control form-control-lg border-primary fw-bold text-uppercase"
                                   placeholder="Scan Kartu RFID Anggota di sini..."
                                   autofocus>
                            <button type="button" class="btn btn-primary btn-lg px-4" id="btn_search_rfid_pengembalian">
                                <i class="bi bi-search me-1"></i> Cari Peminjaman
                            </button>
                        </div>

                        <!-- Status Feedback Box -->
                        <div id="rfid_pengembalian_status" class="alert alert-info py-2 mb-0 d-none"></div>

                        <!-- Card Preview Anggota Scanned -->
                        <div id="anggota_pengembalian_preview_card" class="card border-0 shadow-sm mt-3 d-none" style="border-radius: 12px; background: #ffffff;">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <img id="anggota_pengembalian_preview_foto" src="" class="rounded-circle border" style="width: 70px; height: 70px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 id="anggota_pengembalian_preview_nama" class="fw-bold mb-1 text-dark"></h5>
                                                <div class="text-muted small">NIM: <strong id="anggota_pengembalian_preview_nim" class="text-dark"></strong> | Prodi: <span id="anggota_pengembalian_preview_prodi"></span></div>
                                                <div class="text-muted small mt-1">RFID UID: <span id="anggota_pengembalian_preview_uid" class="badge bg-dark font-monospace"></span></div>
                                            </div>
                                            <div id="anggota_pengembalian_status_badge"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table Active Borrowings for Scanned Member -->
                        <div id="active_loans_container" class="mt-3 d-none">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="bi bi-list-check me-1 text-primary"></i> Daftar Alat Musik yang Sedang Dipinjam Anggota:
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0 bg-white">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Nama Alat Musik</th>
                                            <th>Kode Alat</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Batas Kembali</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="active_loans_body"></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SECTION 2: SCAN BARCODE ALAT MUSIK DIRECT -->
                <div class="card bg-light border-success mb-4" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <label class="form-label fw-bold text-success fs-5 mb-2">
                            <i class="bi bi-qr-code-scan me-2"></i> 2. Atau Scan Barcode Alat Musik Langsung
                        </label>
                        <p class="text-muted small mb-3">
                            Arahkan alat barcode scanner fisik / Kamera HP untuk langsung membuka form pengembalian alat:
                        </p>

                        <div class="input-group mb-2">
                            <span class="input-group-text bg-white text-success border-success">
                                <i class="bi bi-upc-scan fs-4"></i>
                            </span>
                            <input type="text"
                                   id="barcode_pengembalian_input"
                                   class="form-control form-control-lg border-success fw-bold"
                                   placeholder="Scan barcode alat di sini...">
                            <button type="button" class="btn btn-outline-success" id="btn_search_pengembalian">
                                <i class="bi bi-search"></i> Cari & Proses
                            </button>
                            <button type="button" class="btn btn-success" id="btn_toggle_camera_pengembalian">
                                <i class="bi bi-camera-fill me-1"></i> Scan Kamera HP
                            </button>
                        </div>

                        <!-- Box Container Kamera Scanner -->
                        <div id="camera_container_pengembalian" class="mt-3 d-none text-center">
                            <div class="p-2 border rounded bg-white position-relative">
                                <div id="reader_pengembalian" style="width: 100%; max-width: 450px; margin: 0 auto;"></div>
                                <button type="button" id="btn_close_camera_pengembalian" class="btn btn-sm btn-danger mt-2">
                                    <i class="bi bi-x-circle"></i> Tutup Kamera
                                </button>
                            </div>
                        </div>

                        <!-- Status Feedback -->
                        <div id="status_pengembalian" class="mt-3 alert alert-info py-2 d-none"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-secondary px-4 btn-lg">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Peminjaman
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentScannedAnggotaId = null;
    let html5QrCode = null;

    const rfidInput = document.getElementById('rfid_pengembalian_input');
    const btnSearchRfid = document.getElementById('btn_search_rfid_pengembalian');
    const rfidStatus = document.getElementById('rfid_pengembalian_status');
    const loansContainer = document.getElementById('active_loans_container');
    const loansBody = document.getElementById('active_loans_body');

    const previewCard = document.getElementById('anggota_pengembalian_preview_card');
    const previewFoto = document.getElementById('anggota_pengembalian_preview_foto');
    const previewNama = document.getElementById('anggota_pengembalian_preview_nama');
    const previewNim = document.getElementById('anggota_pengembalian_preview_nim');
    const previewProdi = document.getElementById('anggota_pengembalian_preview_prodi');
    const previewUid = document.getElementById('anggota_pengembalian_preview_uid');
    const previewStatusBadge = document.getElementById('anggota_pengembalian_status_badge');

    const barcodeInput = document.getElementById('barcode_pengembalian_input');
    const btnSearch = document.getElementById('btn_search_pengembalian');
    const btnToggleCamera = document.getElementById('btn_toggle_camera_pengembalian');
    const btnCloseCamera = document.getElementById('btn_close_camera_pengembalian');
    const cameraContainer = document.getElementById('camera_container_pengembalian');
    const statusBox = document.getElementById('status_pengembalian');

    // 1. RFID ANGGOTA LOOKUP
    rfidInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performRfidPengembalian(this.value.trim());
        }
    });

    btnSearchRfid.addEventListener('click', function() {
        performRfidPengembalian(rfidInput.value.trim());
    });

    // Reset member preview & loans table when input is cleared
    rfidInput.addEventListener('input', function() {
        if (!this.value.trim()) {
            resetPengembalianAnggotaPreview();
        }
    });

    function resetPengembalianAnggotaPreview() {
        currentScannedAnggotaId = null;
        rfidStatus.className = 'alert alert-info py-2 mb-0 d-none';
        rfidStatus.innerHTML = '';
        loansBody.innerHTML = '';
        loansContainer.classList.add('d-none');
        if (previewCard) previewCard.classList.add('d-none');
    }

    function performRfidPengembalian(uid) {
        if (!uid) {
            resetPengembalianAnggotaPreview();
            return;
        }

        rfidStatus.className = 'alert alert-info py-2 mb-0';
        rfidStatus.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Memeriksa data anggota dan peminjaman aktif...';
        rfidStatus.classList.remove('d-none');

        fetch(`/anggota/find-by-rfid/${encodeURIComponent(uid)}`, {
            headers: { 'Accept': 'application/json', 'bypass-tunnel-reminder': 'true' }
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(res => {
            if (res.status === 200 && res.body.success) {
                const anggota = res.body.anggota;
                currentScannedAnggotaId = anggota.id_anggota;

                rfidStatus.className = 'alert alert-success py-2 mb-0';
                rfidStatus.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Anggota Ditemukan: <strong>${anggota.nama}</strong> (${anggota.nim})`;

                // Render Preview Card
                previewNama.textContent = anggota.nama;
                previewNim.textContent = anggota.nim || '-';
                previewProdi.textContent = anggota.prodi || '-';
                previewUid.textContent = anggota.uid_rfid || uid;

                if (anggota.foto_ktm_url) {
                    previewFoto.src = anggota.foto_ktm_url;
                } else {
                    previewFoto.src = 'https://via.placeholder.com/70?text=KTM';
                }

                if (anggota.status_anggota === 'Aktif') {
                    previewStatusBadge.innerHTML = '<span class="badge bg-success fs-6">Status: Aktif</span>';
                } else {
                    previewStatusBadge.innerHTML = `<span class="badge bg-danger fs-6">Status: ${anggota.status_anggota}</span>`;
                }
                previewCard.classList.remove('d-none');

                // Render Active Loans
                if (anggota.active_loans && anggota.active_loans.length > 0) {
                    let html = '';
                    let totalBorrowedItemsCount = 0;

                    anggota.active_loans.forEach(loan => {
                        if (loan.details && loan.details.length > 0) {
                            loan.details.forEach(detail => {
                                if (detail.status_detail === 'dipinjam') {
                                    totalBorrowedItemsCount++;
                                    const alat = detail.alat || {};
                                    html += `
                                        <tr>
                                            <td><strong class="text-dark">${alat.nama_alat || '-'}</strong></td>
                                            <td><span class="badge bg-secondary font-monospace">${alat.kode_alat || '-'}</span></td>
                                            <td><small class="text-muted">${loan.tanggal_pinjam}</small></td>
                                            <td><small class="text-danger fw-bold">${loan.batas_kembali}</small></td>
                                            <td class="text-center">
                                                <a href="/pengembalian/${loan.id_peminjaman}?auto_detail=${detail.id_detail}" class="btn btn-sm btn-success fw-bold shadow-sm">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Proses Pengembalian
                                                </a>
                                            </td>
                                        </tr>
                                    `;
                                }
                            });
                        } else if (loan.status_pinjam === 'dipinjam' || loan.status_transaksi === 'Aktif') {
                            const alat = loan.alat || {};
                            totalBorrowedItemsCount++;
                            html += `
                                <tr>
                                    <td><strong class="text-dark">${alat.nama_alat || '-'}</strong></td>
                                    <td><span class="badge bg-secondary font-monospace">${alat.kode_alat || '-'}</span></td>
                                    <td><small class="text-muted">${loan.tanggal_pinjam}</small></td>
                                    <td><small class="text-danger fw-bold">${loan.batas_kembali}</small></td>
                                    <td class="text-center">
                                        <a href="/pengembalian/${loan.id_peminjaman}" class="btn btn-sm btn-success fw-bold shadow-sm">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Proses Pengembalian
                                        </a>
                                    </td>
                                </tr>
                            `;
                        }
                    });

                    if (totalBorrowedItemsCount > 0) {
                        loansBody.innerHTML = html;
                        loansContainer.classList.remove('d-none');
                    } else {
                        loansBody.innerHTML = '';
                        loansContainer.classList.add('d-none');
                        rfidStatus.className = 'alert alert-warning py-2 mb-0 mt-2';
                        rfidStatus.innerHTML = `<i class="bi bi-info-circle-fill me-1"></i> Anggota <strong>${anggota.nama}</strong> tidak memiliki alat musik yang sedang dipinjam saat ini.`;
                    }
                } else {
                    loansBody.innerHTML = '';
                    loansContainer.classList.add('d-none');
                    rfidStatus.className = 'alert alert-warning py-2 mb-0 mt-2';
                    rfidStatus.innerHTML = `<i class="bi bi-info-circle-fill me-1"></i> Anggota <strong>${anggota.nama}</strong> saat ini tidak memiliki peminjaman alat musik yang aktif.`;
                }
            } else {
                currentScannedAnggotaId = null;
                rfidStatus.className = 'alert alert-danger py-2 mb-0';
                rfidStatus.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> ${res.body.message || 'Anggota tidak ditemukan.'}`;
                loansContainer.classList.add('d-none');
                previewCard.classList.add('d-none');
            }
        })
        .catch(err => {
            currentScannedAnggotaId = null;
            rfidStatus.className = 'alert alert-danger py-2 mb-0';
            rfidStatus.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Gagal terhubung ke server.';
            loansContainer.classList.add('d-none');
            previewCard.classList.add('d-none');
        });
    }

    let lastHandledScanTime = 0;
    let lastHandledUid = '';

    // Auto polling for ESP32 RFID Card Tap on Return Page
    setInterval(() => {
        if (document.hidden) return;
        fetch('/api/rfid/latest-scanned?type=pengembalian', {
            headers: { 'Accept': 'application/json', 'bypass-tunnel-reminder': 'true' }
        })
        .then(res => res.json())
        .then(res => {
            const uid = res.uid_rfid || (res.data ? res.data.uid_rfid : null);
            const scanTime = res.scan_time || 0;
            if (res.success && uid && (scanTime !== lastHandledScanTime || uid !== lastHandledUid)) {
                lastHandledScanTime = scanTime;
                lastHandledUid = uid;
                rfidInput.value = uid;
                rfidInput.dispatchEvent(new Event('input'));
                rfidInput.dispatchEvent(new Event('change'));
                performRfidPengembalian(uid);
            }
        })
        .catch(err => {});
    }, 2500);

    // 2. BARCODE ALAT MUSIK DIRECT LOOKUP
    barcodeInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            processPengembalianScan(this.value.trim());
        }
    });

    btnSearch.addEventListener('click', function() {
        processPengembalianScan(barcodeInput.value.trim());
    });

    function processPengembalianScan(code) {
        if (!code) return;

        statusBox.className = 'alert alert-info py-2 mt-2';
        statusBox.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Memeriksa data peminjaman di database...';
        statusBox.classList.remove('d-none');

        let searchUrl = `/pengembalian/find-by-barcode/${encodeURIComponent(code)}`;
        if (currentScannedAnggotaId) {
            searchUrl += `?id_anggota=${encodeURIComponent(currentScannedAnggotaId)}`;
        }

        fetch(searchUrl, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'bypass-tunnel-reminder': 'true' }
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(res => {
            if (res.status === 200 && res.body.success) {
                statusBox.className = 'alert alert-success py-2 mt-2';
                statusBox.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> ${res.body.message}. Mengarahkan ke form proses...`;
                setTimeout(() => {
                    window.location.href = res.body.redirect_url;
                }, 500);
            } else {
                statusBox.className = 'alert alert-danger py-2 mt-2';
                statusBox.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> ${res.body.message || 'Data tidak ditemukan.'}`;
            }
        })
        .catch(err => {
            statusBox.className = 'alert alert-danger py-2 mt-2';
            statusBox.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Gagal terhubung ke server.';
        });
    }

    // Toggle Camera Scanner (Html5Qrcode)
    btnToggleCamera.addEventListener('click', function() {
        if (cameraContainer.classList.contains('d-none')) {
            startCamera();
        } else {
            stopCamera();
        }
    });

    btnCloseCamera.addEventListener('click', stopCamera);

    function startCamera() {
        cameraContainer.classList.remove('d-none');
        if (html5QrCode && html5QrCode.isScanning) return;

        html5QrCode = new Html5Qrcode("reader_pengembalian");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        const onScanSuccess = (decodedText) => {
            barcodeInput.value = decodedText;
            processPengembalianScan(decodedText);
            stopCamera();
        };

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
        .catch(err => {
            alert('Tidak dapat membuka kamera HP/Laptop: ' + err);
            cameraContainer.classList.add('d-none');
        });
    }

    function stopCamera() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                cameraContainer.classList.add('d-none');
            });
        } else {
            cameraContainer.classList.add('d-none');
        }
    }

    // PC & Mobile Real-Time Sync Listener
    let lastSyncedStateTime = 0;
    setInterval(() => {
        if (document.hidden) return;
        fetch('/api/pengembalian/sync-state', {
            headers: { 'Accept': 'application/json', 'bypass-tunnel-reminder': 'true' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data) {
                const data = res.data;
                const updatedAt = data.updated_at || 0;
                if (updatedAt > lastSyncedStateTime) {
                    lastSyncedStateTime = updatedAt;
                    if (data.id_peminjaman && (data.action === 'open_detail' || data.action === 'rfid_scanned')) {
                        let targetUrl = `/pengembalian/${data.id_peminjaman}`;
                        if (data.id_detail) {
                            targetUrl += `?auto_detail=${data.id_detail}`;
                        }
                        if (data.auto_scan) {
                            targetUrl += (targetUrl.includes('?') ? '&' : '?') + `auto_scan=${encodeURIComponent(data.auto_scan)}`;
                        }
                        window.location.href = targetUrl;
                    }
                }
            }
        })
        .catch(err => {});
    }, 2500);
});
</script>
@endsection