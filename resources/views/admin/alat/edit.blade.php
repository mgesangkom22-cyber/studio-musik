@extends('layouts.app')

@section('title','Edit Alat Musik')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <span class="badge bg-warning-subtle text-warning-emphasis fw-bold px-3 py-2 rounded-pill mb-1">EDIT INVENTORY</span>
                <h4 class="fw-bold text-dark m-0">
                    <i class="bi bi-pencil-square text-warning me-2"></i> Edit Data Alat Musik
                </h4>
            </div>

            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.alat.update', $alat->id_alat) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Kode Alat -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kode Alat</label>
                            <input type="text" name="kode_alat" class="form-control form-control-lg" value="{{ old('kode_alat', $alat->kode_alat) }}" required>
                        </div>

                        <!-- Nama Alat -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Alat Musik</label>
                            <input type="text" name="nama_alat" class="form-control form-control-lg" value="{{ old('nama_alat', $alat->nama_alat) }}" required>
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="kategori" class="form-select form-select-lg" required>
                                @php
                                    $kategoriList = ['Gitar', 'Bass', 'Keyboard', 'Drum', 'Mikrofon', 'Amplifier', 'Sound System', 'Lainnya'];
                                @endphp
                                @foreach($kategoriList as $item)
                                    <option value="{{ $item }}" {{ old('kategori', $alat->kategori) == $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Maksimal Lama Pinjam -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Maksimal Lama Pinjam (Hari)</label>
                            <input type="number" name="maks_lama_pinjam" class="form-control form-control-lg" value="{{ old('maks_lama_pinjam', $alat->maks_lama_pinjam) }}" min="1" required>
                        </div>

                        <!-- Status Alat -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Status Alat</label>
                            <select name="status_alat" class="form-select form-select-lg" required>
                                @php $st = old('status_alat', $alat->status_alat); @endphp
                                <option value="Tersedia" {{ in_array($st, ['Tersedia', 'tersedia']) ? 'selected' : '' }}>🟢 Tersedia</option>
                                <option value="Dipinjam" {{ in_array($st, ['Dipinjam', 'dipinjam']) ? 'selected' : '' }}>🟡 Dipinjam</option>
                                <option value="Rusak Ringan" {{ in_array($st, ['Rusak Ringan', 'rusak']) ? 'selected' : '' }}>🔴 Rusak Ringan</option>
                                <option value="Rusak Berat" {{ in_array($st, ['Rusak Berat', 'perbaikan', 'maintenance']) ? 'selected' : '' }}>🔴 Rusak Berat</option>
                                <option value="Hilang" {{ in_array($st, ['Hilang', 'hilang']) ? 'selected' : '' }}>❌ Hilang</option>
                            </select>
                        </div>

                        <!-- Foto Saat Ini -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold d-block">Foto Saat Ini</label>
                            @if($alat->foto_alat)
                                <img src="{{ asset('uploads/alat/'.$alat->foto_alat) }}" id="img_preview" class="img-thumbnail rounded-3" style="width: 180px; height: 180px; object-fit: cover;">
                            @else
                                <div id="no_img_preview" class="bg-light border rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 180px; height: 180px;">
                                    <span>Belum ada foto</span>
                                </div>
                            @endif
                        </div>

                        <!-- Ganti Foto -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-bold">Ganti Foto Alat Musik</label>
                            <input type="file" name="foto_alat" id="input_foto" class="form-control" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto alat.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-3 border-top">
                        <a href="{{ route('admin.alat.show', $alat->id_alat) }}" class="btn btn-secondary px-4">
                            <i class="bi bi-arrow-left me-1"></i> Batal / Kembali
                        </a>
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('input_foto')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            let img = document.getElementById('img_preview');
            if (img) {
                img.src = evt.target.result;
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>

@endsection