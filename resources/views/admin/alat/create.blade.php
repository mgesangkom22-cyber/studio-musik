@extends('layouts.app')

@section('title','Tambah Alat Musik')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 18px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill mb-1">INVENTORY MANAGEMENT</span>
                <h4 class="fw-bold text-dark m-0">
                    <i class="bi bi-plus-circle text-success me-2"></i> Tambah Alat Musik Baru
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

                <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <!-- Kode Alat Musik -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kode Alat Musik</label>
                            <input type="text" name="kode_alat" class="form-control form-control-lg" value="{{ old('kode_alat') }}" placeholder="Contoh: ALT0011 (Otomatis jika kosong)">
                            <small class="text-muted">Kode barcode akan otomatis dibuat jika dikosongkan.</small>
                        </div>

                        <!-- Nama Alat Musik -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Alat Musik</label>
                            <input type="text" name="nama_alat" class="form-control form-control-lg" value="{{ old('nama_alat') }}" placeholder="Contoh: Gitar Listrik Fender" required>
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kategori Alat</label>
                            <select name="kategori" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Gitar" {{ old('kategori')=='Gitar' ? 'selected' : '' }}>Gitar</option>
                                <option value="Bass" {{ old('kategori')=='Bass' ? 'selected' : '' }}>Bass</option>
                                <option value="Keyboard" {{ old('kategori')=='Keyboard' ? 'selected' : '' }}>Keyboard</option>
                                <option value="Drum" {{ old('kategori')=='Drum' ? 'selected' : '' }}>Drum</option>
                                <option value="Mikrofon" {{ old('kategori')=='Mikrofon' ? 'selected' : '' }}>Mikrofon</option>
                                <option value="Amplifier" {{ old('kategori')=='Amplifier' ? 'selected' : '' }}>Amplifier</option>
                                <option value="Sound System" {{ old('kategori')=='Sound System' ? 'selected' : '' }}>Sound System</option>
                                <option value="Lainnya" {{ old('kategori')=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Maksimal Lama Pinjam -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Maksimal Lama Pinjam (Hari)</label>
                            <input type="number" name="maks_lama_pinjam" class="form-control form-control-lg" value="{{ old('maks_lama_pinjam', 3) }}" min="1" required>
                        </div>

                        <!-- Foto Alat Musik -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-bold">Foto Alat Musik</label>
                            <input type="file" name="foto_alat" id="create_input_foto" class="form-control" accept="image/*">
                            <div id="preview_box" class="mt-3 d-none">
                                <span class="d-block small text-muted mb-1">Preview Foto:</span>
                                <img id="create_img_preview" src="" class="img-thumbnail rounded-3" style="max-height: 180px; object-fit: cover;">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-3 border-top">
                        <a href="{{ route('admin.alat.index') }}" class="btn btn-secondary px-4">
                            <i class="bi bi-arrow-left me-1"></i> Batal / Kembali
                        </a>
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-check-circle me-1"></i> Simpan Data Alat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('create_input_foto')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            const previewBox = document.getElementById('preview_box');
            const imgPreview = document.getElementById('create_img_preview');
            if (previewBox && imgPreview) {
                imgPreview.src = evt.target.result;
                previewBox.classList.remove('d-none');
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>

@endsection