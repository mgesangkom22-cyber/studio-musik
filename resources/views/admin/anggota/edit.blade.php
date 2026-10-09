@extends('layouts.app')

@section('title','Edit Anggota')

@section('content')

<div class="card shadow border-0">

    <div class="card-header bg-warning">

        <h4 class="mb-0">

            <i class="bi bi-pencil-square"></i>
            Edit Data Anggota

        </h4>

    </div>

    <div class="card-body">

        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form action="{{ route('anggota.update',$anggota->id_anggota) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        NIM
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $anggota->nim }}"
                        readonly>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Nama
                    </label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="{{ old('nama',$anggota->nama) }}"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Program Studi
                    </label>

                    <input
                        type="text"
                        name="prodi"
                        class="form-control"
                        value="{{ old('prodi',$anggota->prodi) }}"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        No HP
                    </label>

                    <input
                        type="text"
                        name="no_hp"
                        class="form-control"
                        value="{{ old('no_hp',$anggota->no_hp) }}"
                        required>

                </div>

                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        class="form-control"
                        rows="3"
                        required>{{ old('alamat',$anggota->alamat) }}</textarea>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email',$anggota->email) }}"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Nomor RFID
                    </label>

                    <input
                        type="text"
                        name="id_rfid"
                        class="form-control"
                        value="{{ old('id_rfid',$anggota->id_rfid) }}"
                        placeholder="Tempel kartu RFID">

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">
                        Status Anggota
                    </label>

                    <select
                        name="status_anggota"
                        class="form-select"
                        required>

                        <option value="Aktif"
                        {{ $anggota->status_anggota=='Aktif' ? 'selected':'' }}>
                            Aktif
                        </option>

                        <option value="Ditangguhkan"
                        {{ $anggota->status_anggota=='Ditangguhkan' ? 'selected':'' }}>
                            Ditangguhkan
                        </option>

                        <option value="Nonaktif"
                        {{ $anggota->status_anggota=='Nonaktif' ? 'selected':'' }}>
                            Nonaktif
                        </option>

                    </select>

                </div>
<div class="col-md-6 mb-3">

    <label class="form-label">
        Tanggal Mulai Sanksi
    </label>

    <input
        type="date"
        name="tanggal_mulai_sanksi"
        class="form-control"
        value="{{ old('tanggal_mulai_sanksi', $anggota->tanggal_mulai_sanksi) }}">

</div>

<div class="col-md-6 mb-3">

    <label class="form-label">
        Tanggal Berakhir Sanksi
    </label>

    <input
        type="date"
        name="tanggal_berakhir_sanksi"
        class="form-control"
        value="{{ old('tanggal_berakhir_sanksi', $anggota->tanggal_berakhir_sanksi) }}">

</div>
            </div>

           <a href="{{ route('admin.anggota.index') }}"
   class="btn btn-secondary">

    <i class="bi bi-arrow-left"></i>
    Kembali

</a>
            <button
                type="submit"
                class="btn btn-success">

                <i class="bi bi-check-circle"></i>
                Simpan Perubahan

            </button>

        </form>

    </div>

</div>

@endsection