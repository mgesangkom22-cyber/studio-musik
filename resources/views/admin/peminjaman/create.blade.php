@extends('layouts.app')

@section('title','Tambah Peminjaman')

@section('content')

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow border-0" style="border-radius: 20px;">
            <div class="card-header bg-success text-white py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-box-arrow-in-down me-2"></i> Peminjaman Alat Musik (RFID & Barcode Multi-Alat)
                    </h4>
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                        <i class="bi bi-broadcast text-success me-1"></i> ESP32 RC522 Ready
                    </span>
                </div>
            </div>

            <div class="card-body p-4">

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- SECTION 1: SCAN RFID ANGGOTA -->
                <div class="card bg-light border-primary mb-4" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <label class="form-label fw-bold text-primary fs-5 mb-2">
                            <i class="bi bi-person-badge-fill me-2"></i> 1. Scan RFID Kartu Anggota Peminjam
                        </label>
                        <p class="text-muted small mb-3">
                            Tempelkan kartu RFID anggota pada sensor ESP32 RC522, atau ketik UID RFID / NIM di bawah:
                        </p>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white text-primary border-primary">
                                <i class="bi bi-broadcast fs-4"></i>
                            </span>
                            <input type="text"
                                   id="rfid_input"
                                   class="form-control form-control-lg border-primary fw-bold text-uppercase"
                                   placeholder="Scan Kartu RFID Anggota di sini atau ketik UID..."
                                   autofocus>
                            <button type="button" class="btn btn-primary btn-lg px-4" id="btn_search_rfid">
                                <i class="bi bi-search me-1"></i> Cari Anggota
                            </button>
                        </div>

                        <!-- Live Status Scanning Box -->
                        <div id="rfid_scan_status" class="alert alert-info py-2 mb-0 d-none">
                            <i class="bi bi-hourglass-split me-1"></i> Memeriksa data anggota...
                        </div>

                        <!-- Card Preview Anggota Scanned -->
                        <div id="anggota_preview_card" class="card border-0 shadow-sm mt-3 d-none" style="border-radius: 12px; background: #ffffff;">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div id="anggota_foto_container" class="me-3">
                                        <img id="anggota_preview_foto" src="" class="rounded-circle border" style="width: 75px; height: 75px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 id="anggota_preview_nama" class="fw-bold mb-1 text-dark"></h5>
                                                <div class="text-muted small">NIM: <strong id="anggota_preview_nim" class="text-dark"></strong> | Prodi: <span id="anggota_preview_prodi"></span></div>
                                                <div class="text-muted small mt-1">RFID UID: <span id="anggota_preview_uid" class="badge bg-dark font-monospace"></span></div>
                                            </div>
                                            <div id="anggota_status_badge"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SECTION 2: SCAN BARCODE ALAT MUSIK -->
                <div class="card bg-light border-success mb-4" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <label class="form-label fw-bold text-success fs-5 mb-2">
                            <i class="bi bi-qr-code-scan me-2"></i> 2. Scan Barcode / Pilih Alat Musik
                        </label>
                        <p class="text-muted small mb-3">
                            Arahkan scanner barcode ke alat musik atau pilih dari dropdown untuk menambahkan ke daftar pinjam:
                        </p>

                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white text-success border-success">
                                <i class="bi bi-upc-scan fs-4"></i>
                            </span>
                            <input type="text"
                                   id="barcode_input"
                                   class="form-control form-control-lg border-success fw-bold"
                                   placeholder="Scan barcode alat musik di sini...">
                            <button type="button" class="btn btn-outline-success" id="btn_search_barcode">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Alat
                            </button>
                            <button type="button" class="btn btn-success" id="btn_toggle_camera">
                                <i class="bi bi-camera-fill me-1"></i> Scan Kamera HP
                            </button>
                        </div>

                        <!-- Dropdown Manual Option -->
                        <div class="row align-items-center g-2 mb-2">
                            <div class="col-md-9">
                                <select id="select_id_alat" class="form-select form-select-lg border-success">
                                    <option value="">-- Atau Pilih Alat Musik dari Daftar --</option>
                                    @foreach($alat as $item)
                                        <option value="{{ $item->id_alat }}"
                                                data-kode="{{ $item->kode_alat }}"
                                                data-barcode="{{ $item->barcode_alat ?? $item->kode_alat }}"
                                                data-nama="{{ $item->nama_alat }}"
                                                data-kategori="{{ $item->kategori }}"
                                                data-maks="{{ $item->maks_lama_pinjam }}"
                                                data-status="{{ $item->status_alat }}"
                                                data-foto="{{ $item->foto_alat ? asset('uploads/alat/'.$item->foto_alat) : '' }}">
                                            {{ $item->nama_alat }} ({{ $item->kode_alat }}) - [Maks: {{ $item->maks_lama_pinjam }} Hari]
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-success btn-lg w-100 fw-bold" id="btn_tambah_manual">
                                    <i class="bi bi-cart-plus me-1"></i> Tambah
                                </button>
                            </div>
                        </div>

                        <!-- Box Container Kamera Scanner -->
                        <div id="camera_container" class="mt-3 d-none text-center">
                            <div class="p-2 border rounded bg-white position-relative">
                                <div id="reader" style="width: 100%; max-width: 450px; margin: 0 auto;"></div>
                                <button type="button" id="btn_close_camera" class="btn btn-sm btn-danger mt-2">
                                    <i class="bi bi-x-circle"></i> Tutup Kamera
                                </button>
                            </div>
                        </div>

                        <!-- Scan Barcode Status Feedback -->
                        <div id="barcode_scan_status" class="mt-2 alert alert-info py-2 d-none"></div>

                    </div>
                </div>

                <!-- FORM UTAMA SUBMIT PEMINJAMAN MULTI-ALAT -->
                <form action="{{ route('admin.peminjaman.store') }}" method="POST" id="form_peminjaman">
                    @csrf

                    <!-- Dropdown Anggota (Auto-Selected via RFID) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Anggota Peminjam Selected</label>
                        <select name="id_anggota" id="select_id_anggota" class="form-select form-select-lg" required>
                            <option value="">-- Pilih atau Scan RFID Anggota --</option>
                            @foreach($anggota as $item)
                                <option value="{{ $item->id_anggota }}" 
                                        data-rfid="{{ $item->uid_rfid ?? $item->id_rfid }}"
                                        data-nim="{{ $item->nim }}"
                                        data-nama="{{ $item->nama }}"
                                        data-status="{{ $item->status_anggota }}">
                                    {{ $item->nama }} (NIM: {{ $item->nim ?? '-' }}) - [RFID: {{ $item->uid_rfid ?? $item->id_rfid ?? 'Belum ada' }}]
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SECTION 3: DAFTAR KERANJANG ALAT YANG DIPINJAM -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: #ffffff;">
                        <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-basket-fill text-warning me-2"></i> Daftar Alat Musik yang Dipinjam (Keranjang)
                            </h5>
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" id="badge_total_alat">
                                Total: 0 Alat
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="table_daftar_alat">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 60px;">No</th>
                                            <th style="width: 80px;">Foto</th>
                                            <th>Kode Alat</th>
                                            <th>Nama Alat Musik</th>
                                            <th>Kategori</th>
                                            <th>Maks Pinjam</th>
                                            <th class="text-center" style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_daftar_alat">
                                        <tr id="tr_empty_alat">
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-1 d-block text-secondary opacity-50 mb-2"></i>
                                                Belum ada alat musik yang ditambahkan. Silakan scan barcode atau pilih alat dari daftar di atas.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-2">
                        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary px-4 btn-lg">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success btn-lg px-5 fw-bold shadow-sm" id="btn_submit_peminjaman">
                            <i class="bi bi-check-circle-fill me-1"></i> Simpan Transaksi Peminjaman (1 Header - Multi Alat)
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rfidInput = document.getElementById('rfid_input');
    const btnSearchRfid = document.getElementById('btn_search_rfid');
    const rfidScanStatus = document.getElementById('rfid_scan_status');
    const selectAnggota = document.getElementById('select_id_anggota');

    const anggotaPreviewCard = document.getElementById('anggota_preview_card');
    const anggotaPreviewFoto = document.getElementById('anggota_preview_foto');
    const anggotaPreviewNama = document.getElementById('anggota_preview_nama');
    const anggotaPreviewNim = document.getElementById('anggota_preview_nim');
    const anggotaPreviewProdi = document.getElementById('anggota_preview_prodi');
    const anggotaPreviewUid = document.getElementById('anggota_preview_uid');
    const anggotaStatusBadge = document.getElementById('anggota_status_badge');
    const btnSubmit = document.getElementById('btn_submit_peminjaman');

    const barcodeInput = document.getElementById('barcode_input');
    const btnSearchBarcode = document.getElementById('btn_search_barcode');
    const btnToggleCamera = document.getElementById('btn_toggle_camera');
    const btnCloseCamera = document.getElementById('btn_close_camera');
    const cameraContainer = document.getElementById('camera_container');
    const barcodeScanStatus = document.getElementById('barcode_scan_status');
    const selectAlat = document.getElementById('select_id_alat');
    const btnTambahManual = document.getElementById('btn_tambah_manual');

    const tbodyDaftarAlat = document.getElementById('tbody_daftar_alat');
    const trEmptyAlat = document.getElementById('tr_empty_alat');
    const badgeTotalAlat = document.getElementById('badge_total_alat');

    let html5QrCode = null;
    let selectedAlatSet = new Set();
    let selectedAlatMap = new Map();
    let lastSyncTimestamp = 0;
    let isSyncingRemote = false;

    // Push local state (member + cart items) to shared backend cache for multi-client sync
    function syncStateToBackend(action = 'update') {
        const idAnggota = selectAnggota.value || null;
        const selectedAlat = Array.from(selectedAlatMap.values());
        fetch('/api/peminjaman/sync-state', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'bypass-tunnel-reminder': 'true'
            },
            body: JSON.stringify({
                id_anggota: idAnggota,
                selected_alat: selectedAlat,
                action: action
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data && data.data.updated_at) {
                lastSyncTimestamp = data.data.updated_at;
            }
        })
        .catch(err => {});
    }

    // --- 1. RFID ANGGOTA SCANNING LOGIC ---
    rfidInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performRfidSearch(this.value.trim());
        }
    });

    btnSearchRfid.addEventListener('click', function() {
        performRfidSearch(rfidInput.value.trim());
    });

    selectAnggota.addEventListener('change', function() {
        if (this.value) {
            const selectedOpt = this.options[this.selectedIndex];
            const rfid = selectedOpt.dataset.rfid;
            if (rfid && rfid !== 'null') {
                performRfidSearch(rfid);
            } else {
                anggotaPreviewNama.textContent = selectedOpt.dataset.nama || '';
                anggotaPreviewNim.textContent = selectedOpt.dataset.nim || '-';
                anggotaPreviewUid.textContent = selectedOpt.dataset.rfid || '-';
                anggotaPreviewCard.classList.remove('d-none');
                btnSubmit.disabled = false;
                syncStateToBackend('update');
            }
        } else {
            resetAnggotaPreview();
        }
    });

    rfidInput.addEventListener('input', function() {
        if (!this.value.trim()) {
            resetAnggotaPreview();
        }
    });

    function resetAnggotaPreview(syncRemote = true) {
        selectAnggota.value = '';
        rfidScanStatus.className = 'alert alert-info py-2 mb-0 d-none';
        rfidScanStatus.innerHTML = '';
        anggotaPreviewCard.classList.add('d-none');
        anggotaPreviewNama.textContent = '';
        anggotaPreviewNim.textContent = '';
        anggotaPreviewProdi.textContent = '';
        anggotaPreviewUid.textContent = '';
        anggotaStatusBadge.innerHTML = '';
        btnSubmit.disabled = false;

        // Clear local cart
        selectedAlatSet.clear();
        selectedAlatMap.clear();
        const rows = tbodyDaftarAlat.querySelectorAll('tr.tr-alat-row');
        rows.forEach(r => r.remove());
        updateTableState();

        if (syncRemote) {
            syncStateToBackend('clear');
        }
    }

    function performRfidSearch(uid) {
        if (!uid) {
            resetAnggotaPreview();
            return;
        }

        rfidScanStatus.className = 'alert alert-info py-2 mb-0';
        rfidScanStatus.innerHTML = '<i class="bi bi-hourglass-split"></i> Mencari data anggota berdasarkan RFID...';
        rfidScanStatus.classList.remove('d-none');

        fetch(`/anggota/find-by-rfid/${encodeURIComponent(uid)}`, {
            headers: {
                'Accept': 'application/json',
                'bypass-tunnel-reminder': 'true'
            }
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(res => {
            if (res.status === 200 && res.body.success) {
                const anggota = res.body.anggota;
                const hasActiveTrx = res.body.has_active_transaction || (anggota.active_loans && anggota.active_loans.length > 0);

                if (hasActiveTrx) {
                    rfidScanStatus.className = 'alert alert-warning py-2 mb-0';
                    rfidScanStatus.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> Anggota <strong>${anggota.nama}</strong> masih memiliki transaksi peminjaman yang belum selesai.`;
                    alert("Anggota masih memiliki transaksi peminjaman yang belum selesai. Silakan selesaikan transaksi tersebut terlebih dahulu.");
                    btnSubmit.disabled = true;
                    selectAnggota.value = '';
                    anggotaPreviewCard.classList.add('d-none');
                    return;
                }

                rfidScanStatus.className = 'alert alert-success py-2 mb-0';
                rfidScanStatus.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Anggota Ditemukan: <strong>${anggota.nama}</strong> (${anggota.nim})`;

                anggotaPreviewNama.textContent = anggota.nama;
                anggotaPreviewNim.textContent = anggota.nim || '-';
                anggotaPreviewProdi.textContent = anggota.prodi || '-';
                anggotaPreviewUid.textContent = anggota.uid_rfid || uid;

                if (anggota.foto_ktm_url) {
                    anggotaPreviewFoto.src = anggota.foto_ktm_url;
                } else {
                    anggotaPreviewFoto.src = 'https://via.placeholder.com/75?text=KTM';
                }

                if (anggota.status_anggota && anggota.status_anggota.toLowerCase() === 'aktif') {
                    anggotaStatusBadge.innerHTML = '<span class="badge bg-success fs-6">Status: Aktif</span>';
                    btnSubmit.disabled = false;
                } else {
                    anggotaStatusBadge.innerHTML = `<span class="badge bg-danger fs-6">Status: ${anggota.status_anggota}</span>`;
                    alert(`Perhatian! Anggota ${anggota.nama} berstatus "${anggota.status_anggota}". Anggota ini memiliki sanksi atau denda yang belum diselesaikan.`);
                    btnSubmit.disabled = true;
                }

                selectAnggota.value = anggota.id_anggota;
                anggotaPreviewCard.classList.remove('d-none');
                syncStateToBackend('update');
            } else {
                rfidScanStatus.className = 'alert alert-danger py-2 mb-0';
                rfidScanStatus.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> ${res.body.message || 'Kartu RFID tidak ditemukan.'}`;
                anggotaPreviewCard.classList.add('d-none');
            }
        })
        .catch(err => {
            rfidScanStatus.className = 'alert alert-danger py-2 mb-0';
            rfidScanStatus.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Gagal terhubung ke server.';
        });
    }

    let lastHandledScanTime = 0;
    let lastHandledUid = '';

    // ESP32 Live Auto Polling for RFID Tap in Borrowing Page
    setInterval(() => {
        if (document.hidden) return;
        fetch('/api/rfid/latest-scanned?type=peminjaman', {
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
                performRfidSearch(uid);
            }
        })
        .catch(err => {});
    }, 2500);

    // Multi-Client Real-Time Borrowing Sync Polling (PC <-> HP)
    setInterval(() => {
        if (document.hidden || isSyncingRemote) return;
        fetch('/api/peminjaman/sync-state', {
            headers: { 'Accept': 'application/json', 'bypass-tunnel-reminder': 'true' }
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success || !res.data) return;
            const state = res.data;
            if (!state.updated_at || state.updated_at <= lastSyncTimestamp) return;

            isSyncingRemote = true;
            lastSyncTimestamp = state.updated_at;

            // 1. Sync Anggota if state has id_anggota and differs from selectAnggota.value
            if (state.id_anggota && String(selectAnggota.value) !== String(state.id_anggota)) {
                selectAnggota.value = state.id_anggota;
                const selectedOpt = selectAnggota.options[selectAnggota.selectedIndex];
                if (selectedOpt) {
                    const rfid = selectedOpt.dataset.rfid;
                    if (rfid && rfid !== 'null') {
                        rfidInput.value = rfid;
                        performRfidSearch(rfid);
                    } else {
                        anggotaPreviewNama.textContent = selectedOpt.dataset.nama || '';
                        anggotaPreviewNim.textContent = selectedOpt.dataset.nim || '-';
                        anggotaPreviewUid.textContent = selectedOpt.dataset.rfid || '-';
                        anggotaPreviewCard.classList.remove('d-none');
                        btnSubmit.disabled = false;
                    }
                }
            } else if (!state.id_anggota && selectAnggota.value && state.action === 'clear') {
                resetAnggotaPreview(false);
            }

            // 2. Sync Cart Items
            const remoteAlatList = Array.isArray(state.selected_alat) ? state.selected_alat : [];
            const remoteIds = new Set(remoteAlatList.map(item => String(item.id_alat)));

            // Remove items locally that are no longer in remote list
            Array.from(selectedAlatSet).forEach(id => {
                if (!remoteIds.has(String(id))) {
                    removeAlatFromTable(id, false);
                }
            });

            // Add items locally that exist in remote list but not locally
            remoteAlatList.forEach(item => {
                const idStr = String(item.id_alat);
                if (!selectedAlatSet.has(idStr)) {
                    addAlatToTable(item, false, false);
                }
            });

            isSyncingRemote = false;
        })
        .catch(err => {
            isSyncingRemote = false;
        });
    }, 1000);

    // --- 2. MULTI-ITEM ALAT MUSIK CART LOGIC ---
    barcodeInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performBarcodeSearch(this.value.trim());
        }
    });

    btnSearchBarcode.addEventListener('click', function() {
        performBarcodeSearch(barcodeInput.value.trim());
    });

    btnTambahManual.addEventListener('click', function() {
        const val = selectAlat.value;
        if (!val) {
            alert('Silakan pilih alat musik dari dropdown terlebih dahulu.');
            return;
        }
        const opt = selectAlat.options[selectAlat.selectedIndex];
        addAlatToTable({
            id_alat: val,
            kode_alat: opt.dataset.kode,
            nama_alat: opt.dataset.nama,
            kategori: opt.dataset.kategori,
            maks_lama_pinjam: opt.dataset.maks,
            status_alat: opt.dataset.status,
            foto_url: opt.dataset.foto || 'https://via.placeholder.com/60?text=Alat'
        }, true, true);
        selectAlat.value = '';
    });

    function performBarcodeSearch(code) {
        if (!code) return;

        if (!selectAnggota.value) {
            alert('Silakan scan RFID anggota terlebih dahulu.');
            barcodeScanStatus.className = 'alert alert-warning py-2 mt-2';
            barcodeScanStatus.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> Silakan scan RFID anggota terlebih dahulu.';
            barcodeScanStatus.classList.remove('d-none');
            rfidInput.focus();
            return;
        }

        barcodeScanStatus.className = 'alert alert-info py-2 mt-2';
        barcodeScanStatus.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Mencari alat musik di database...';
        barcodeScanStatus.classList.remove('d-none');

        fetch(`/alat/find-by-barcode/${encodeURIComponent(code)}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'bypass-tunnel-reminder': 'true' }
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(res => {
            if (res.status === 200 && res.body.success) {
                const data = res.body.data;
                addAlatToTable(data, true, true);
                barcodeInput.value = '';
            } else {
                barcodeScanStatus.className = 'alert alert-danger py-2 mt-2';
                barcodeScanStatus.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> ${res.body.message || 'Alat tidak ditemukan.'}`;
            }
        })
        .catch(err => {
            barcodeScanStatus.className = 'alert alert-danger py-2 mt-2';
            barcodeScanStatus.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Gagal terhubung ke server.';
        });
    }

    function addAlatToTable(data, syncToBackend = true, showToastAlert = true) {
        const idAlat = String(data.id_alat);

        const stAlat = data.status_alat ? data.status_alat.toLowerCase() : '';
        if (stAlat !== 'tersedia') {
            barcodeScanStatus.className = 'alert alert-danger py-2 mt-2';
            barcodeScanStatus.innerHTML = `<i class="bi bi-x-circle-fill me-1"></i> Ditolak: Alat <strong>${data.nama_alat}</strong> berstatus <strong>${data.status_alat.toUpperCase()}</strong> (Tidak Tersedia).`;
            if (showToastAlert) {
                alert(`Perhatian! Alat ${data.nama_alat} berstatus "${data.status_alat}" dan tidak tersedia untuk dipinjam.`);
            }
            return;
        }

        if (selectedAlatSet.has(idAlat)) {
            barcodeScanStatus.className = 'alert alert-warning py-2 mt-2';
            barcodeScanStatus.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> Alat <strong>${data.nama_alat}</strong> sudah ada di dalam daftar pinjam.`;
            if (showToastAlert) {
                alert(`Alat musik "${data.nama_alat}" sudah dimasukkan ke dalam daftar pinjam.`);
            }
            return;
        }

        selectedAlatSet.add(idAlat);
        selectedAlatMap.set(idAlat, data);

        if (trEmptyAlat) {
            trEmptyAlat.style.display = 'none';
        }

        const rowCount = tbodyDaftarAlat.querySelectorAll('tr.tr-alat-row').length + 1;
        const fotoUrl = data.foto_url || 'https://via.placeholder.com/60?text=Alat';

        const tr = document.createElement('tr');
        tr.className = 'tr-alat-row';
        tr.id = `tr_alat_${idAlat}`;
        tr.innerHTML = `
            <td class="text-center fw-bold row-no">${rowCount}</td>
            <td>
                <img src="${fotoUrl}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
            </td>
            <td>
                <strong class="font-monospace text-dark">${data.kode_alat}</strong>
                <input type="hidden" name="id_alat[]" value="${idAlat}">
            </td>
            <td>
                <strong class="text-success">${data.nama_alat}</strong>
            </td>
            <td><span class="badge bg-info text-dark">${data.kategori || '-'}</span></td>
            <td><span class="badge bg-secondary">${data.maks_lama_pinjam || 1} Hari</span></td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-alat" data-id="${idAlat}">
                    <i class="bi bi-trash-fill"></i> Hapus
                </button>
            </td>
        `;

        tbodyDaftarAlat.appendChild(tr);

        // Attach Event listener to remove button
        tr.querySelector('.btn-remove-alat').addEventListener('click', function() {
            removeAlatFromTable(idAlat, true);
        });

        barcodeScanStatus.className = 'alert alert-success py-2 mt-2';
        barcodeScanStatus.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Alat <strong>${data.nama_alat}</strong> (${data.kode_alat}) berhasil ditambahkan ke keranjang!`;
        updateTableState();

        if (syncToBackend) {
            syncStateToBackend('update');
        }
    }

    function removeAlatFromTable(idAlat, syncToBackend = true) {
        const idStr = String(idAlat);
        const tr = document.getElementById(`tr_alat_${idStr}`);
        if (tr) {
            tr.remove();
        }
        selectedAlatSet.delete(idStr);
        selectedAlatMap.delete(idStr);
        updateTableState();

        if (syncToBackend) {
            syncStateToBackend('update');
        }
    }

    function updateTableState() {
        const rows = tbodyDaftarAlat.querySelectorAll('tr.tr-alat-row');
        badgeTotalAlat.textContent = `Total: ${rows.length} Alat`;

        if (rows.length === 0) {
            if (trEmptyAlat) trEmptyAlat.style.display = '';
        } else {
            if (trEmptyAlat) trEmptyAlat.style.display = 'none';
            // Renumber rows
            rows.forEach((row, index) => {
                const noEl = row.querySelector('.row-no');
                if (noEl) noEl.textContent = index + 1;
            });
        }
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

        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        const onScanSuccess = (decodedText) => {
            barcodeInput.value = decodedText;
            performBarcodeSearch(decodedText);
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

    // Form Submit Validation
    document.getElementById('form_peminjaman').addEventListener('submit', function(e) {
        if (!selectAnggota.value) {
            e.preventDefault();
            alert('Silakan scan atau pilih Anggota Peminjam terlebih dahulu.');
            rfidInput.focus();
            return;
        }

        const rows = tbodyDaftarAlat.querySelectorAll('tr.tr-alat-row');
        if (rows.length === 0) {
            e.preventDefault();
            alert('Silakan scan barcode atau pilih minimal 1 alat musik untuk dipinjam.');
            barcodeInput.focus();
            return;
        }
    });
});
</script>
@endsection