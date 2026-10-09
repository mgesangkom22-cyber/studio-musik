@extends('layouts.app')

@section('title','Detail Anggota')

@section('content')

<div class="card shadow border-0">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">
            <i class="bi bi-person-vcard-fill"></i>
            Detail Anggota
        </h4>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4 text-center">

                @if($anggota->foto_ktm)

                  <img src="{{ asset('uploads/ktm/'.$anggota->foto_ktm) }}"
                    class="img-thumbnail"
                    style="width:250px; height:330px; object-fit:cover;"
                    alt="Foto KTM">

                @else

                    <img src="https://via.placeholder.com/250x320?text=Tidak+Ada+Foto"
                         class="img-thumbnail">

                @endif

            </div>

            <div class="col-md-8">

                <table class="table table-bordered">

                    <tr>
                        <th width="35%">NIM</th>
                        <td>{{ $anggota->nim }}</td>
                    </tr>

                    <tr>
                        <th>Nama</th>
                        <td>{{ $anggota->nama }}</td>
                    </tr>

                    <tr>
                        <th>Program Studi</th>
                        <td>{{ $anggota->prodi }}</td>
                    </tr>

                    <tr>
                        <th>Alamat</th>
                        <td>{{ $anggota->alamat }}</td>
                    </tr>

                    <tr>
                        <th>No HP</th>
                        <td>{{ $anggota->no_hp }}</td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td>{{ $anggota->email }}</td>
                    </tr>

                    <tr>
                        <th>Nomor RFID</th>

                        <td>

                            @if($anggota->id_rfid)

                                <span class="badge bg-success">
                                    {{ $anggota->id_rfid }}
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Belum Terdaftar
                                </span>

                            @endif

                        </td>

                    </tr>

                    <tr>
                        <th>Status Anggota</th>

                        <td>

                            @if($anggota->status_anggota == 'Aktif')

                                <span class="badge bg-success">
                                    Aktif
                                </span>

                            @elseif($anggota->status_anggota == 'Ditangguhkan')

                                <span class="badge bg-warning text-dark">
                                    Ditangguhkan
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Nonaktif
                                </span>

                            @endif

                        </td>

                    </tr>

                    <tr>
                        <th>Tanggal Mulai Sanksi</th>

                        <td>

                            {{ $anggota->tanggal_mulai_sanksi ?? '-' }}

                        </td>

                    </tr>

                    <tr>
                        <th>Tanggal Berakhir Sanksi</th>

                        <td>

                            {{ $anggota->tanggal_berakhir_sanksi ?? '-' }}

                        </td>

                    </tr>

                </table>

            <div class="mt-3">

    <a href="{{ route('admin.anggota.index') }}"
        class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>
        Kembali

    </a>

    <a href="{{ route('anggota.edit',$anggota->id_anggota) }}"
        class="btn btn-warning">

        <i class="bi bi-pencil-square"></i>
        Edit Data

    </a>

    <form
        action="{{ route('anggota.destroy',$anggota->id_anggota) }}"
        method="POST"
        class="d-inline"
        onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="btn btn-danger">

            <i class="bi bi-trash"></i>
            Hapus

        </button>

    </form>

</div>

            </div>

        </div>

    </div>

</div>

@endsection