@extends('layouts.app')

@section('title','Data Anggota')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow border-0" style="border-radius: 16px;">
    <div class="card-header bg-success text-white py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-people-fill me-2"></i> Data Anggota Studio Musik
            </h4>
            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                <i class="bi bi-broadcast me-1 text-success"></i> RFID Integration Ready
            </span>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Live Search Bar & Filter Status Anggota -->
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="member_search" class="form-control bg-light border-start-0 ps-0" placeholder="Cari nama, NIM, email, atau prodi anggota...">
                </div>
            </div>
            <div class="col-md-7">
                <div class="d-flex flex-wrap gap-2 justify-content-md-end" id="status_filter_group">
                    <button type="button" class="btn btn-sm btn-outline-success active btn-filter-status" data-status="all">
                        <i class="bi bi-grid-fill me-1"></i> Semua
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success btn-filter-status" data-status="aktif">
                        🟢 Aktif
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning text-dark btn-filter-status" data-status="ditangguhkan">
                        🟡 Ditangguhkan
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-filter-status" data-status="nonaktif">
                        🔴 Nonaktif
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" id="table_anggota">
                <thead class="table-success text-dark">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>NIM</th>
                        <th>Nama Anggota</th>
                        <th>Program Studi</th>
                        <th>Status RFID Card</th>
                        <th>Status Anggota</th>
                        <th class="text-center" style="width: 250px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($anggota as $item)
                    @php
                        $rfidCode = $item->uid_rfid ?? $item->id_rfid;
                        $stAnggotaLower = strtolower($item->status_anggota);
                    @endphp
                    <tr class="member-row-item" data-status="{{ $stAnggotaLower }}">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><strong class="text-dark">{{ $item->nim }}</strong></td>
                        <td>
                            <strong class="d-block text-dark">{{ $item->nama }}</strong>
                            <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $item->email }}</small>
                        </td>
                        <td>{{ $item->prodi }}</td>
                        <td>
                            @if($rfidCode)
                                <span class="badge bg-success px-3 py-2 fs-6">
                                    <i class="bi bi-card-heading me-1"></i> {{ $rfidCode }}
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-card-slash me-1"></i> Belum Ada RFID
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->status_anggota == 'Aktif')
                                <span class="badge bg-success">Aktif</span>
                            @elseif($item->status_anggota == 'Ditangguhkan')
                                <span class="badge bg-warning text-dark">Ditangguhkan</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <button type="button" 
                                        class="btn btn-warning btn-sm fw-semibold btn-rfid-modal"
                                        data-id="{{ $item->id_anggota }}"
                                        data-nama="{{ $item->nama }}"
                                        data-nim="{{ $item->nim }}"
                                        data-rfid="{{ $rfidCode }}">
                                    <i class="bi bi-broadcast me-1"></i> Registrasi RFID
                                </button>
                                <a href="/anggota/{{ $item->id_anggota }}" class="btn btn-info btn-sm text-white">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="/anggota/{{ $item->id_anggota }}/edit" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data anggota.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Registrasi RFID Realtime -->
<div class="modal fade" id="modalRfidRegister" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalRfidLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-success text-white py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                <h5 class="modal-title fw-bold" id="modalRfidLabel">
                    <i class="bi bi-broadcast me-2"></i> Registrasi Kartu RFID Anggota
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Info Member -->
                <div class="card bg-light border-0 mb-3">
                    <div class="card-body p-3">
                        <small class="text-muted text-uppercase fw-semibold d-block">Anggota Pendaftar</small>
                        <h5 id="modal_member_nama" class="fw-bold text-success mb-1">-</h5>
                        <div class="text-muted small">NIM: <span id="modal_member_nim" class="fw-bold text-dark">-</span></div>
                    </div>
                </div>

                <!-- Live Status Scanner ESP32 -->
                <div id="rfid_status_box" class="alert alert-warning text-center py-4 mb-3 rounded-4 border-2 border-warning">
                    <div class="spinner-grow text-warning mb-2" role="status" style="width: 2.5rem; height: 2.5rem;"></div>
                    <h5 class="fw-bold mb-1 text-dark" id="rfid_status_title">Menunggu Scan Kartu RFID...</h5>
                    <p class="mb-0 text-muted small" id="rfid_status_desc">
                        Silakan tempelkan kartu RFID baru pada sensor RFID RC522 (ESP32).
                    </p>
                </div>

                <!-- Form Input UID -->
                <form id="form_save_rfid">
                    @csrf
                    <input type="hidden" id="modal_member_id" name="id_anggota">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">
                            <i class="bi bi-credit-card-2-front-fill me-1 text-success"></i> UID RFID Kartu
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   id="modal_uid_rfid" 
                                   name="uid_rfid" 
                                   class="form-control form-control-lg fw-bold text-success text-center font-monospace" 
                                   placeholder="Contoh: 04A35FC2"
                                   required>
                            <button type="button" class="btn btn-outline-secondary" id="btn_clear_rfid_input">
                                <i class="bi bi-x-circle"></i> Clear
                            </button>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            UID otomatis terisi saat ESP32 membaca kartu, atau bisa diketik secara manual.
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm" id="btn_submit_rfid">
                            <i class="bi bi-check-circle-fill me-1"></i> Simpan RFID Anggota
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalRfid = new bootstrap.Modal(document.getElementById('modalRfidRegister'));
    const modalNama = document.getElementById('modal_member_nama');
    const modalNim = document.getElementById('modal_member_nim');
    const modalId = document.getElementById('modal_member_id');
    const inputUid = document.getElementById('modal_uid_rfid');
    const formSave = document.getElementById('form_save_rfid');

    const statusBox = document.getElementById('rfid_status_box');
    const statusTitle = document.getElementById('rfid_status_title');
    const statusDesc = document.getElementById('rfid_status_desc');

    let pollingInterval = null;
    const btnSubmit = document.getElementById('btn_submit_rfid');

    function validateUidRealtime(uid, memberId) {
        if (!uid || uid.trim() === '') {
            btnSubmit.disabled = false;
            return;
        }

        fetch(`/anggota/check-rfid-availability?uid=${encodeURIComponent(uid)}&id_anggota=${encodeURIComponent(memberId || '')}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.available) {
                btnSubmit.disabled = false;
                statusBox.className = 'alert alert-success text-center py-3 mb-3 rounded-4 border-2 border-success';
                statusBox.innerHTML = `
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-2 d-block"></i>
                    <h5 class="fw-bold mb-1 text-success">${res.message}</h5>
                    <p class="mb-0 text-dark small">Kartu RFID <strong class="font-monospace">${uid}</strong> siap didaftarkan.</p>
                `;
            } else {
                btnSubmit.disabled = true;
                statusBox.className = 'alert alert-danger text-center py-3 mb-3 rounded-4 border-2 border-danger';
                statusBox.innerHTML = `
                    <i class="bi bi-x-circle-fill text-danger fs-1 mb-2 d-block"></i>
                    <h5 class="fw-bold mb-1 text-danger">${res.message}</h5>
                    <p class="mb-0 text-dark small">Silakan gunakan kartu RFID yang berbeda.</p>
                `;
            }
        })
        .catch(err => console.error('Validation error:', err));
    }

    inputUid.addEventListener('input', function() {
        validateUidRealtime(this.value.trim(), modalId.value);
    });

    // Open Modal Registration
    document.querySelectorAll('.btn-rfid-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const nama = this.dataset.nama;
            const nim = this.dataset.nim;
            const rfid = this.dataset.rfid;

            modalId.value = id;
            modalNama.textContent = nama;
            modalNim.textContent = nim;

            if (rfid && rfid !== 'null' && rfid.trim() !== '') {
                inputUid.value = rfid;
                statusBox.className = 'alert alert-info text-center py-3 mb-3 rounded-4 border-2 border-info';
                statusBox.innerHTML = `
                    <i class="bi bi-info-circle-fill text-info fs-2 mb-1 d-block"></i>
                    <h5 class="fw-bold mb-1 text-dark">Anggota ini sudah memiliki kartu RFID.</h5>
                    <p class="mb-2 text-muted small">UID Terdaftar saat ini: <strong class="font-monospace text-dark">${rfid}</strong></p>
                    <div class="d-flex justify-content-center gap-2 mt-2">
                        <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Batalkan
                        </button>
                        <button type="button" class="btn btn-sm btn-warning text-dark fw-bold px-3" id="btn_ganti_rfid">
                            <i class="bi bi-arrow-repeat me-1"></i> Ganti Kartu RFID
                        </button>
                    </div>
                `;
                stopRfidPolling();
                modalRfid.show();

                setTimeout(() => {
                    document.getElementById('btn_ganti_rfid')?.addEventListener('click', function() {
                        inputUid.value = '';
                        btnSubmit.disabled = false;
                        resetStatusBox();
                        startRfidPolling();
                    });
                }, 100);
            } else {
                inputUid.value = '';
                btnSubmit.disabled = false;
                resetStatusBox();
                modalRfid.show();
                startRfidPolling();
            }
        });
    });

    document.getElementById('modalRfidRegister').addEventListener('hidden.bs.modal', function () {
        stopRfidPolling();
    });

    let lastHandledRegScanTime = 0;

    function startRfidPolling() {
        stopRfidPolling();
        
        pollingInterval = setInterval(() => {
            fetch('/api/rfid/latest-scanned?type=register', {
                headers: {
                    'Accept': 'application/json',
                    'bypass-tunnel-reminder': 'true'
                }
            })
            .then(res => res.json())
            .then(res => {
                const uid = res.uid_rfid || (res.data ? res.data.uid_rfid : null);
                const scanTime = res.scan_time || 0;
                if (res.success && uid && (scanTime !== lastHandledRegScanTime || inputUid.value !== uid)) {
                    lastHandledRegScanTime = scanTime;
                    inputUid.value = uid;
                    validateUidRealtime(uid, modalId.value);
                }
            })
            .catch(err => console.error('Polling error:', err));
        }, 1500);
    }

    function stopRfidPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    function resetStatusBox() {
        btnSubmit.disabled = false;
        statusBox.className = 'alert alert-warning text-center py-4 mb-3 rounded-4 border-2 border-warning';
        statusBox.innerHTML = `
            <div class="spinner-grow text-warning mb-2" role="status" style="width: 2.5rem; height: 2.5rem;"></div>
            <h5 class="fw-bold mb-1 text-dark">Menunggu Scan Kartu RFID...</h5>
            <p class="mb-0 text-muted small">
                Silakan tempelkan kartu RFID baru pada sensor RFID RC522 (ESP32).
            </p>
        `;
    }

    document.getElementById('btn_clear_rfid_input').addEventListener('click', function() {
        inputUid.value = '';
        btnSubmit.disabled = false;
        resetStatusBox();
        fetch('/api/rfid/clear-latest', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    });

    // Handle Save RFID Form Submit via AJAX
    formSave.addEventListener('submit', function(e) {
        e.preventDefault();
        const memberId = modalId.value;
        const uid = inputUid.value.trim();

        if (btnSubmit.disabled) {
            alert('Kartu RFID ini tidak dapat disimpan karena sudah terdaftar pada anggota lain.');
            return;
        }

        if (!uid) {
            alert('Silakan scan kartu RFID atau masukkan UID secara manual terlebih dahulu.');
            return;
        }

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';

        fetch(`/anggota/${memberId}/update-rfid`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ uid_rfid: uid })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(res => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Simpan RFID Anggota';

            if (res.status === 200 && res.body.success) {
                alert(res.body.message);
                modalRfid.hide();
                window.location.reload();
            } else {
                alert(res.body.message || 'Gagal menyimpan RFID.');
            }
        })
        .catch(err => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Simpan RFID Anggota';
            alert('Terjadi kesalahan jaringan atau sistem.');
        });
    });

    // Live Search & Status Filter Logic for Anggota
    const searchInputMember = document.getElementById('member_search');
    const filterButtonsMember = document.querySelectorAll('.btn-filter-status');
    const memberRows = document.querySelectorAll('.member-row-item');
    let currentStatusFilter = 'all';

    function filterMemberTable() {
        const query = searchInputMember ? searchInputMember.value.toLowerCase().trim() : '';

        memberRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const status = row.dataset.status ? row.dataset.status.toLowerCase() : '';

            const matchesQuery = !query || text.includes(query);
            const matchesStatus = (currentStatusFilter === 'all') || (status === currentStatusFilter);

            if (matchesQuery && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (searchInputMember) {
        searchInputMember.addEventListener('input', filterMemberTable);
    }

    filterButtonsMember.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtonsMember.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentStatusFilter = this.dataset.status ? this.dataset.status.toLowerCase() : 'all';
            filterMemberTable();
        });
    });
});
</script>
@endsection