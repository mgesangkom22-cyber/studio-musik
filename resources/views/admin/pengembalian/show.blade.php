@extends('layouts.app')

@section('title','Proses Pengembalian Multi-Alat')

@section('content')

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

@php
    $unreturnedDetails = $peminjaman->details ? $peminjaman->details->where('status_detail', 'dipinjam') : collect();
    $returnedDetails = $peminjaman->details ? $peminjaman->details->where('status_detail', 'dikembalikan') : collect();
    $totalCount = $peminjaman->details ? $peminjaman->details->count() : 1;
    $unreturnedCount = $unreturnedDetails->count();
@endphp

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill mb-1">RETURN PROCESS</span>
                    <h4 class="fw-bold text-dark m-0">
                        <i class="bi bi-arrow-return-left text-success me-2"></i> Proses Pengembalian Alat Musik
                    </h4>
                </div>
                <div>
                    <span class="badge bg-dark font-monospace px-3 py-2 fs-6 me-2">{{ $peminjaman->kode_transaksi ?? ('TRX-'.$peminjaman->id_peminjaman) }}</span>
                    @if($unreturnedCount === 0)
                        <span class="badge bg-success px-3 py-2 fs-6">🟢 Transaksi Selesai</span>
                    @else
                        <span class="badge bg-warning text-dark px-3 py-2 fs-6">🟡 Dipinjam (Sisa {{ $unreturnedCount }} Alat)</span>
                    @endif
                </div>
            </div>

            <div class="card-body p-4">

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show fw-bold mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show fw-bold mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <strong class="d-block mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i> Gagal Memproses Pengembalian:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Header Info Peminjam & Jadwal -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-person-badge me-2"></i> Anggota Peminjam</h6>
                            <strong class="d-block text-dark fs-5">{{ $peminjaman->anggota->nama ?? '-' }}</strong>
                            <small class="text-muted d-block">NIM: {{ $peminjaman->anggota->nim ?? '-' }} | Prodi: {{ $peminjaman->anggota->prodi ?? '-' }}</small>
                            <small class="text-muted d-block mt-1">RFID UID: <span class="badge bg-secondary font-monospace">{{ $peminjaman->anggota->uid_rfid ?? $peminjaman->anggota->id_rfid ?? '-' }}</span></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        @php
                            $tanggalKembali = \Carbon\Carbon::today();
                            $batasKembali = \Carbon\Carbon::parse($peminjaman->batas_kembali);
                            $hariTerlambat = 0;
                            if ($tanggalKembali->gt($batasKembali)) {
                                $hariTerlambat = (int) $batasKembali->diffInDays($tanggalKembali);
                            }
                            $dendaKeterlambatan = $hariTerlambat * 10000;
                        @endphp
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <h6 class="fw-bold text-info mb-2"><i class="bi bi-calendar-event me-2"></i> Jadwal Transaksi & Keterlambatan</h6>
                            <div class="small mb-1">Tanggal Pinjam: <strong class="text-dark">{{ $peminjaman->tanggal_pinjam }}</strong></div>
                            <div class="small mb-1">Batas Pengembalian: <strong class="text-danger">{{ $peminjaman->batas_kembali }}</strong></div>
                            <div class="mt-2">
                                @if($hariTerlambat > 0)
                                    <span class="badge bg-danger fs-6"><i class="bi bi-exclamation-triangle me-1"></i> Terlambat {{ $hariTerlambat }} Hari (Denda Auto: Rp {{ number_format($dendaKeterlambatan, 0, ',', '.') }})</span>
                                @else
                                    <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i> Tepat Waktu (0 Hari)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION BARCODE SCANNER PADA DETAIL PENGEMBALIAN -->
                @if($unreturnedCount > 0)
                    <div class="card bg-light border-success mb-4" style="border-radius: 16px;">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <label class="form-label fw-bold text-success fs-5 mb-0">
                                    <i class="bi bi-qr-code-scan me-2"></i> Scan Barcode Alat Musik
                                </label>
                                <button type="button" class="btn btn-success btn-sm fw-bold shadow-sm" id="btn_toggle_camera_show">
                                    <i class="bi bi-camera-fill me-1"></i> Scan Kamera HP
                                </button>
                            </div>
                            <p class="text-muted small mb-3">
                                Arahkan alat barcode scanner / kamera HP untuk verifikasi pengembalian item secara langsung:
                            </p>

                            <div class="input-group">
                                <span class="input-group-text bg-white text-success border-success">
                                    <i class="bi bi-upc-scan fs-4"></i>
                                </span>
                                <input type="text"
                                       id="barcode_show_input"
                                       class="form-control form-control-lg border-success fw-bold"
                                       placeholder="Scan barcode alat di sini..."
                                       autofocus>
                                <button type="button" class="btn btn-success fw-bold px-4" id="btn_search_show">
                                    <i class="bi bi-search me-1"></i> Cari Alat
                                </button>
                            </div>

                            <!-- Box Container Kamera Scanner -->
                            <div id="camera_container_show" class="mt-3 d-none text-center">
                                <div class="p-2 border rounded bg-white position-relative">
                                    <div id="reader_show" style="width: 100%; max-width: 450px; margin: 0 auto;"></div>
                                    <button type="button" id="btn_close_camera_show" class="btn btn-sm btn-danger mt-2">
                                        <i class="bi bi-x-circle me-1"></i> Tutup Kamera
                                    </button>
                                </div>
                            </div>

                            <!-- Status Feedback Alert -->
                            <div id="status_show_alert" class="alert alert-info py-2 mb-0 mt-3 d-none"></div>
                        </div>
                    </div>
                @endif

                <!-- TABEL DAFTAR ALAT DALAM TRANSAKSI -->
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="bi bi-music-note-list text-success me-2"></i> Daftar Alat Musik yang Dipinjam (Total: {{ $totalCount }} Alat)
                </h5>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-dark text-white">
                            <tr>
                                <th width="50" class="text-center">No</th>
                                <th width="70">Foto</th>
                                <th>Kode Alat</th>
                                <th>Nama Alat Musik</th>
                                <th>Kategori</th>
                                <th>Status Item</th>
                                <th class="text-center" width="180">Aksi Pengembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($peminjaman->details && $peminjaman->details->count() > 0)
                                @foreach($peminjaman->details as $detail)
                                    @php
                                        $alatItem = $detail->alat;
                                        $fotoUrl = ($alatItem && $alatItem->foto_alat) ? asset('uploads/alat/' . $alatItem->foto_alat) : 'https://via.placeholder.com/60?text=Alat';
                                        $isItemReturned = $detail->status_detail === 'dikembalikan';
                                    @endphp
                                    <tr>
                                        <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $fotoUrl }}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td><strong class="font-monospace text-primary">{{ $alatItem->kode_alat ?? '-' }}</strong></td>
                                        <td>
                                            <strong class="text-dark d-block">{{ $alatItem->nama_alat ?? '-' }}</strong>
                                            @if($isItemReturned && $detail->kondisi_dikembalikan)
                                                <small class="text-muted">Kondisi: <strong>{{ $detail->kondisi_dikembalikan }}</strong></small>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-info text-dark">{{ $alatItem->kategori ?? '-' }}</span></td>
                                        <td>
                                            @if($isItemReturned)
                                                <span class="badge bg-success">🟢 Dikembalikan ({{ \Carbon\Carbon::parse($detail->tanggal_dikembalikan)->format('d-m-Y') }})</span>
                                            @else
                                                <span class="badge bg-warning text-dark">🟡 Sedang Dipinjam</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!$isItemReturned)
                                                <button type="button" class="btn btn-success btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#modalKembalikanItem{{ $detail->id_detail }}">
                                                    <i class="bi bi-arrow-return-left me-1"></i> Kembalikan Alat Ini
                                                </button>
                                            @else
                                                <span class="badge bg-light text-success border"><i class="bi bi-check-all me-1"></i> Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $alatItem = $peminjaman->alat;
                                    $fotoUrl = ($alatItem && $alatItem->foto_alat) ? asset('uploads/alat/' . $alatItem->foto_alat) : 'https://via.placeholder.com/60?text=Alat';
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold">1</td>
                                    <td>
                                        <img src="{{ $fotoUrl }}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td><strong class="font-monospace text-primary">{{ $alatItem->kode_alat ?? '-' }}</strong></td>
                                    <td><strong class="text-dark">{{ $alatItem->nama_alat ?? '-' }}</strong></td>
                                    <td><span class="badge bg-info text-dark">{{ $alatItem->kategori ?? '-' }}</span></td>
                                    <td><span class="badge bg-warning text-dark">🟡 Sedang Dipinjam</span></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-success btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#modalKembalikanItemLegacy">
                                            <i class="bi bi-arrow-return-left me-1"></i> Kembalikan Alat Ini
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pengembalian
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ================================================================= -->
<!-- MODAL SECTION (Ditempatkan di luar tabel agar HTML DOM valid)      -->
<!-- ================================================================= -->

@if($peminjaman->details && $peminjaman->details->count() > 0)
    @foreach($peminjaman->details as $detail)
        @if($detail->status_detail !== 'dikembalikan')
            @php
                $alatItem = $detail->alat;
                $fotoUrl = ($alatItem && $alatItem->foto_alat) ? asset('uploads/alat/' . $alatItem->foto_alat) : 'https://via.placeholder.com/60?text=Alat';
                $isDamagedItem = in_array(old('kondisi_alat'), ['Rusak Ringan', 'Rusak Berat', 'Hilang']);
            @endphp
            <div class="modal fade text-start" id="modalKembalikanItem{{ $detail->id_detail }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title fw-bold">
                                <i class="bi bi-clipboard-check me-2"></i> Verifikasi Pengembalian Alat
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.pengembalian.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_peminjaman" value="{{ $peminjaman->id_peminjaman }}">
                            <input type="hidden" name="id_detail" value="{{ $detail->id_detail }}">
                            <input type="hidden" name="id_alat" value="{{ $alatItem->id_alat ?? '' }}">

                            <div class="modal-body p-4">
                                <div class="p-3 bg-light rounded-3 border mb-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $fotoUrl }}" class="rounded me-3 border" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <strong class="d-block text-success fs-6">{{ $alatItem->nama_alat ?? '-' }}</strong>
                                            <small class="text-muted font-monospace">Kode: {{ $alatItem->kode_alat ?? '-' }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Kondisi Alat Saat Dikembalikan <span class="text-danger">*</span></label>
                                    <select name="kondisi_alat" class="form-select form-select-lg kondisi-select-item" required>
                                        <option value="">-- Pilih Kondisi Alat --</option>
                                        <option value="Baik" {{ old('kondisi_alat') == 'Baik' ? 'selected' : '' }}>🟢 Baik (Normal / Tanpa Kerusakan)</option>
                                        <option value="Rusak Ringan" {{ old('kondisi_alat') == 'Rusak Ringan' ? 'selected' : '' }}>🟡 Rusak Ringan</option>
                                        <option value="Rusak Berat" {{ old('kondisi_alat') == 'Rusak Berat' ? 'selected' : '' }}>🔴 Rusak Berat</option>
                                        <option value="Hilang" {{ old('kondisi_alat') == 'Hilang' ? 'selected' : '' }}>❌ Hilang</option>
                                    </select>
                                </div>

                                <div class="denda-box-wrapper mb-3 p-3 border border-warning rounded-3 bg-warning-subtle" style="display: {{ $isDamagedItem ? 'block' : 'none' }};">
                                    <label class="form-label fw-bold text-dark">Nominal Ganti Rugi (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" name="denda_kerusakan" class="form-control form-control-lg fw-bold denda-input-field" placeholder="Masukkan nominal (contoh: 50000)" min="1" value="{{ old('denda_kerusakan') }}">
                                    <small class="text-muted d-block mt-1">Nominal denda ganti rugi yang ditentukan admin (harus > Rp0).</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark label-keterangan">Catatan / Keterangan {!! $isDamagedItem ? '<span class="text-danger">* (Wajib diisi)</span>' : '<small class="text-muted">(Opsional)</small>' !!}</label>
                                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan kondisi alat / alasan kerusakan...">{{ old('keterangan') }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success px-4 fw-bold">
                                    <i class="bi bi-check-circle me-1"></i> Simpan Pengembalian Alat Ini
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@else
    @php
        $alatItem = $peminjaman->alat;
        $fotoUrl = ($alatItem && $alatItem->foto_alat) ? asset('uploads/alat/' . $alatItem->foto_alat) : 'https://via.placeholder.com/60?text=Alat';
        $isDamagedLegacy = in_array(old('kondisi_alat'), ['Rusak Ringan', 'Rusak Berat', 'Hilang']);
    @endphp
    <div class="modal fade text-start" id="modalKembalikanItemLegacy" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-clipboard-check me-2"></i> Verifikasi Pengembalian Alat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.pengembalian.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_peminjaman" value="{{ $peminjaman->id_peminjaman }}">
                    <input type="hidden" name="id_alat" value="{{ $alatItem->id_alat ?? '' }}">

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Kondisi Alat Saat Dikembalikan <span class="text-danger">*</span></label>
                            <select name="kondisi_alat" class="form-select form-select-lg kondisi-select-item" required>
                                <option value="">-- Pilih Kondisi Alat --</option>
                                <option value="Baik" {{ old('kondisi_alat') == 'Baik' ? 'selected' : '' }}>🟢 Baik (Normal / Tanpa Kerusakan)</option>
                                <option value="Rusak Ringan" {{ old('kondisi_alat') == 'Rusak Ringan' ? 'selected' : '' }}>🟡 Rusak Ringan</option>
                                <option value="Rusak Berat" {{ old('kondisi_alat') == 'Rusak Berat' ? 'selected' : '' }}>🔴 Rusak Berat</option>
                                <option value="Hilang" {{ old('kondisi_alat') == 'Hilang' ? 'selected' : '' }}>❌ Hilang</option>
                            </select>
                        </div>

                        <div class="denda-box-wrapper mb-3 p-3 border border-warning rounded-3 bg-warning-subtle" style="display: {{ $isDamagedLegacy ? 'block' : 'none' }};">
                            <label class="form-label fw-bold text-dark">Nominal Ganti Rugi (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="denda_kerusakan" class="form-control form-control-lg fw-bold denda-input-field" placeholder="Masukkan nominal (contoh: 50000)" min="1" value="{{ old('denda_kerusakan') }}">
                            <small class="text-muted d-block mt-1">Nominal denda ganti rugi yang ditentukan admin (harus > Rp0).</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark label-keterangan">Catatan / Keterangan {!! $isDamagedLegacy ? '<span class="text-danger">* (Wajib diisi)</span>' : '<small class="text-muted">(Opsional)</small>' !!}</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan kondisi alat / alasan kerusakan...">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4 fw-bold">Simpan Pengembalian Alat Ini</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    function processKondisiChange(selectEl) {
        if (!selectEl) return;
        const modalBody = selectEl.closest('.modal-body') || selectEl.closest('form');
        if (!modalBody) return;

        const val = selectEl.value ? selectEl.value.trim() : '';
        const dendaBox = modalBody.querySelector('.denda-box-wrapper');
        const inputDenda = modalBody.querySelector('.denda-input-field');
        const inputKeterangan = modalBody.querySelector('textarea[name="keterangan"]');
        const labelKeterangan = modalBody.querySelector('.label-keterangan');

        if (['Rusak Ringan', 'Rusak Berat', 'Hilang'].includes(val)) {
            if (dendaBox) {
                dendaBox.style.display = 'block';
            }
            if (inputDenda) {
                inputDenda.required = true;
                inputDenda.removeAttribute('disabled');
            }
            if (inputKeterangan) {
                inputKeterangan.required = true;
            }
            if (labelKeterangan) {
                labelKeterangan.innerHTML = 'Catatan / Keterangan <span class="text-danger">* (Wajib diisi)</span>';
            }
        } else {
            if (dendaBox) {
                dendaBox.style.display = 'none';
            }
            if (inputDenda) {
                inputDenda.required = false;
            }
            if (inputKeterangan) {
                inputKeterangan.required = false;
            }
            if (labelKeterangan) {
                labelKeterangan.innerHTML = 'Catatan / Keterangan <small class="text-muted">(Opsional)</small>';
            }
        }
    }

    // Attach listener using document delegation
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('kondisi-select-item')) {
            processKondisiChange(e.target);
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target && e.target.classList.contains('kondisi-select-item')) {
            processKondisiChange(e.target);
        }
    });

    // Check all selects on modal show
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function() {
            const selectEl = this.querySelector('.kondisi-select-item');
            if (selectEl) {
                processKondisiChange(selectEl);
            }
        });
    });

    @if($errors->any())
        // Auto open modal if validation errors exist
        const firstModal = document.querySelector('.modal');
        if (firstModal && typeof bootstrap !== 'undefined') {
            try {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(firstModal);
                modalInstance.show();
            } catch(e) {}
        }
    @endif

    // Barcode scanner & lookup logic on show.blade.php
    let html5QrCodeShow = null;
    const barcodeShowInput = document.getElementById('barcode_show_input');
    const btnSearchShow = document.getElementById('btn_search_show');
    const btnToggleCameraShow = document.getElementById('btn_toggle_camera_show');
    const btnCloseCameraShow = document.getElementById('btn_close_camera_show');
    const cameraContainerShow = document.getElementById('camera_container_show');
    const statusShowAlert = document.getElementById('status_show_alert');
    const idPeminjaman = "{{ $peminjaman->id_peminjaman }}";

    function showStatusMessage(message, type = 'info') {
        if (!statusShowAlert) return;
        statusShowAlert.className = `alert alert-${type} py-2 mb-0 mt-3`;
        statusShowAlert.innerHTML = message;
        statusShowAlert.classList.remove('d-none');
    }

    function hideStatusMessage() {
        if (!statusShowAlert) return;
        statusShowAlert.classList.add('d-none');
    }

    function processBarcodeScanShow(code) {
        if (!code) return;
        showStatusMessage('<i class="bi bi-hourglass-split me-1"></i> Memeriksa barcode alat dalam transaksi ini...', 'info');

        fetch(`/pengembalian/find-by-barcode/${encodeURIComponent(code)}?id_peminjaman=${idPeminjaman}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'bypass-tunnel-reminder': 'true' }
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(res => {
            if (res.status === 200 && res.body.success) {
                hideStatusMessage();
                if (barcodeShowInput) barcodeShowInput.value = '';

                const detailId = res.body.id_detail;
                let targetModalId = detailId ? `#modalKembalikanItem${detailId}` : '#modalKembalikanItemLegacy';
                const modalEl = document.querySelector(targetModalId);

                if (modalEl && typeof bootstrap !== 'undefined') {
                    const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modalInstance.show();
                } else {
                    showStatusMessage('<i class="bi bi-exclamation-triangle-fill me-1"></i> Form pengembalian alat tidak ditemukan di halaman.', 'danger');
                }
            } else {
                showStatusMessage(`<i class="bi bi-exclamation-triangle-fill me-1"></i> ${res.body.message || 'Barcode alat tidak dapat diproses.'}`, 'danger');
            }
        })
        .catch(err => {
            showStatusMessage('<i class="bi bi-x-circle-fill me-1"></i> Gagal terhubung ke server.', 'danger');
        });
    }

    if (barcodeShowInput) {
        barcodeShowInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                processBarcodeScanShow(this.value.trim());
            }
        });
    }

    if (btnSearchShow) {
        btnSearchShow.addEventListener('click', function() {
            if (barcodeShowInput) processBarcodeScanShow(barcodeShowInput.value.trim());
        });
    }

    if (btnToggleCameraShow) {
        btnToggleCameraShow.addEventListener('click', function() {
            if (cameraContainerShow.classList.contains('d-none')) {
                startCameraShow();
            } else {
                stopCameraShow();
            }
        });
    }

    if (btnCloseCameraShow) {
        btnCloseCameraShow.addEventListener('click', stopCameraShow);
    }

    function startCameraShow() {
        if (!cameraContainerShow) return;
        cameraContainerShow.classList.remove('d-none');
        if (html5QrCodeShow && html5QrCodeShow.isScanning) return;

        html5QrCodeShow = new Html5Qrcode("reader_show");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        const onScanSuccess = (decodedText) => {
            if (barcodeShowInput) barcodeShowInput.value = decodedText;
            processBarcodeScanShow(decodedText);
            stopCameraShow();
        };

        html5QrCodeShow.start({ facingMode: "environment" }, config, onScanSuccess)
        .catch(err => {
            alert('Tidak dapat membuka kamera HP/Laptop: ' + err);
            cameraContainerShow.classList.add('d-none');
        });
    }

    function stopCameraShow() {
        if (!cameraContainerShow) return;
        if (html5QrCodeShow && html5QrCodeShow.isScanning) {
            html5QrCodeShow.stop().then(() => {
                html5QrCodeShow.clear();
                cameraContainerShow.classList.add('d-none');
            });
        } else {
            cameraContainerShow.classList.add('d-none');
        }
    }

    // Auto-open modal if auto_detail or auto_scan is passed in URL
    const urlParams = new URLSearchParams(window.location.search);
    const autoDetailId = urlParams.get('auto_detail');
    const autoScanCode = urlParams.get('auto_scan');

    function tryAutoOpenModal() {
        if (autoDetailId) {
            const targetModalId = `#modalKembalikanItem${autoDetailId}`;
            const modalEl = document.querySelector(targetModalId);
            if (modalEl) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    try {
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                        modalInstance.show();
                        return true;
                    } catch(e) {}
                }
            }
        }
        if (autoScanCode) {
            processBarcodeScanShow(autoScanCode);
            return true;
        }
        return false;
    }

    if (!tryAutoOpenModal()) {
        setTimeout(tryAutoOpenModal, 200);
        setTimeout(tryAutoOpenModal, 600);
    }

    // PC & Mobile Real-Time Sync Listener
    let lastSyncedStateTime = 0;
    const currentPeminjamanId = parseInt("{{ $peminjaman->id_peminjaman }}");

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

                    if (data.action === 'completed') {
                        window.location.href = "{{ route('admin.pengembalian.index') }}";
                    } else if (data.id_peminjaman && parseInt(data.id_peminjaman) === currentPeminjamanId) {
                        if (data.action === 'open_detail' && data.id_detail) {
                            const targetModalId = `#modalKembalikanItem${data.id_detail}`;
                            const modalEl = document.querySelector(targetModalId);
                            if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                try {
                                    const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                                    modalInstance.show();
                                } catch(e) {}
                            }
                        } else if (data.action === 'item_returned') {
                            window.location.reload();
                        }
                    } else if (data.id_peminjaman && parseInt(data.id_peminjaman) !== currentPeminjamanId) {
                        let targetUrl = `/pengembalian/${data.id_peminjaman}`;
                        if (data.id_detail) targetUrl += `?auto_detail=${data.id_detail}`;
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