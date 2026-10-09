@extends('layouts.app')

@section('title','Detail Pendaftaran')

@section('content')

<div class="card shadow border-0">

    <div class="card-header bg-success text-white">

        <h4>
            Detail Pendaftaran Anggota
        </h4>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-8">

                <table class="table">

                    <tr>
                        <th width="220">Nama</th>
                        <td>{{ $pendaftaran->nama }}</td>
                    </tr>

                    <tr>
                        <th>NIM</th>
                        <td>{{ $pendaftaran->nim }}</td>
                    </tr>

                    <tr>
                        <th>Program Studi</th>
                        <td>{{ $pendaftaran->prodi }}</td>
                    </tr>

                    <tr>
                        <th>Alamat</th>
                        <td>{{ $pendaftaran->alamat }}</td>
                    </tr>

                    <tr>
                        <th>No HP</th>
                        <td>{{ $pendaftaran->no_hp }}</td>
                    </tr>
<tr>
    <th>Email</th>
    <td>{{ $pendaftaran->email }}</td>
</tr>
                    <tr>
                        <th>Status</th>
                        <td>

                            <span class="badge bg-warning">

                                {{ ucfirst($pendaftaran->status_verifikasi) }}

                            </span>

                        </td>

                    </tr>

                </table>

            </div>

            <div class="col-md-4 text-center">

                <label class="fw-bold">

                    Foto KTM

                </label>

                <br><br>

                <img
                    src="{{ asset('uploads/ktm/'.$pendaftaran->foto_ktm) }}"
                    class="img-fluid rounded border">

            </div>

        </div>

        <hr>

       <div class="d-flex gap-2">

    <a href="/pendaftaran-anggota"
       class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>

        Kembali

    </a>

    @if($pendaftaran->status_verifikasi == 'menunggu')

        <form action="{{ route('admin.pendaftaran.terima', $pendaftaran->id_pendaftaran) }}"
              method="POST"
              class="d-inline">

            @csrf

            <button type="submit" class="btn btn-success">

                <i class="bi bi-check-circle"></i>

                Terima

            </button>

        </form>

       <button type="button"
        class="btn btn-danger"
        data-bs-toggle="modal"
        data-bs-target="#modalTolak">

    <i class="bi bi-x-circle"></i>

    Tolak

</button>

    @elseif($pendaftaran->status_verifikasi == 'diterima')

        <span class="btn btn-success disabled">

            <i class="bi bi-check-circle-fill"></i>

            Sudah Diverifikasi

        </span>

    @elseif($pendaftaran->status_verifikasi == 'ditolak')

        <span class="btn btn-danger disabled">

            <i class="bi bi-x-circle-fill"></i>

            Pendaftaran Ditolak

        </span>

    @endif

</div>
@if($pendaftaran->status_verifikasi == 'ditolak')

<tr>

    <th>Catatan Admin</th>

    <td>

        <div class="alert alert-danger mb-0">

            {{ $pendaftaran->catatan_admin }}

        </div>

    </td>

</tr>

@endif

    </div>

</div>
<!-- Modal Tolak -->
<div class="modal fade"
     id="modalTolak"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('admin.pendaftaran.tolak', $pendaftaran->id_pendaftaran) }}"
                  method="POST">

                @csrf

                <div class="modal-header bg-danger text-white">

                    <h5 class="modal-title">

                        Tolak Pendaftaran

                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <label class="form-label">

                        Catatan Admin

                    </label>

                    <textarea
                        name="catatan_admin"
                        class="form-control"
                        rows="4"
                        placeholder="Masukkan alasan penolakan..."
                        required></textarea>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="bi bi-x-circle"></i>

                        Simpan Penolakan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
@endsection