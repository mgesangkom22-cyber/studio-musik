@extends('layouts.app')

@section('title','Pendaftaran Anggota')

@section('content')

@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

    {{ session('success') }}

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>

</div>

@endif

@if(session('error'))

<div class="alert alert-danger alert-dismissible fade show">

    {{ session('error') }}

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>

</div>

@endif
<div class="card shadow border-0">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">
            <i class="bi bi-person-plus-fill"></i>
            Pendaftaran Anggota
        </h4>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

               <table class="table table-bordered table-hover align-middle">

    <thead class="table-success">

        <tr>

            <th>No</th>

            <th>Nama</th>

            <th>NIM</th>

            <th>Program Studi</th>

            <th>Status Verifikasi</th>

            <th>Status Email</th>

            <th>Tanggal Daftar</th>

            <th>Aksi</th>

        </tr>

    </thead>

    <tbody>

    @forelse($pendaftaran as $item)

    <tr>

        <td>{{ $loop->iteration }}</td>

        <td>{{ $item->nama }}</td>

        <td>{{ $item->nim }}</td>

        <td>{{ $item->prodi }}</td>

        {{-- Status Verifikasi --}}
        <td>

            @if($item->status_verifikasi == 'menunggu')

                <span class="badge bg-warning text-dark">
                    <i class="bi bi-hourglass-split"></i>
                    Menunggu
                </span>

            @elseif($item->status_verifikasi == 'diterima')

                <span class="badge bg-success">
                    <i class="bi bi-check-circle-fill"></i>
                    Diterima
                </span>

            @elseif($item->status_verifikasi == 'ditolak')

                <span class="badge bg-danger">
                    <i class="bi bi-x-circle-fill"></i>
                    Ditolak
                </span>

            @endif

        </td>

        {{-- Status Email --}}
        <td>

            @if($item->status_email == 'pending')

                <span class="badge bg-warning text-dark">
                    <i class="bi bi-envelope"></i>
                    Pending
                </span>

            @elseif($item->status_email == 'terkirim')

                <span class="badge bg-success">
                    <i class="bi bi-envelope-check-fill"></i>
                    Terkirim
                </span>

            @elseif($item->status_email == 'gagal')

                <span class="badge bg-danger">
                    <i class="bi bi-envelope-x-fill"></i>
                    Gagal
                </span>

            @endif

        </td>

        {{-- Tanggal Daftar --}}
        <td>

            {{ date('d-m-Y', strtotime($item->created_at)) }}

        </td>

        {{-- Aksi --}}
        <td>

            <a href="/pendaftaran-anggota/{{ $item->id_pendaftaran }}"
               class="btn btn-info btn-sm">

                <i class="bi bi-eye-fill"></i>

                Detail

            </a>

        </td>

    </tr>

    @empty

    <tr>

        <td colspan="8" class="text-center">

            Belum ada pendaftaran.

        </td>

    </tr>

    @endforelse

    </tbody>

</table>

        </div>

    </div>

</div>

@endsection