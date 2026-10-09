<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:11px;
        }

        h2{
            text-align:center;
            margin-bottom:5px;
        }

        p{
            text-align:center;
            margin:3px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table, th, td{
            border:1px solid #000;
        }

        th{
            background:#d9d9d9;
            text-align:center;
        }

        th, td{
            padding:6px;
        }

    </style>

</head>

<body>

<h2>LAPORAN {{ strtoupper($jenis) }}</h2>

<p>
Periode :
{{ \Carbon\Carbon::parse($tanggal_awal)->format('d-m-Y') }}
s/d
{{ \Carbon\Carbon::parse($tanggal_akhir)->format('d-m-Y') }}
</p>

{{-- ========================= PEMINJAMAN ========================= --}}

@if($jenis == 'peminjaman')

<table>

<thead>

<tr>
    <th>No</th>
    <th>Tanggal Pinjam</th>
    <th>Nama Anggota</th>
    <th>Alat Musik</th>
    <th>Batas Kembali</th>
    <th>Status</th>
</tr>

</thead>

<tbody>

@foreach($data as $item)

<tr>

    <td>{{ $loop->iteration }}</td>

    <td>
        {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') }}
    </td>

    <td>{{ $item->anggota->nama ?? '-' }}</td>

    <td>
        @if($item->details && $item->details->count() > 0)
            {{ $item->details->map(fn($d) => $d->alat->nama_alat ?? '')->filter()->join(', ') }}
        @else
            {{ $item->alat->nama_alat ?? '-' }}
        @endif
    </td>

    <td>
        {{ \Carbon\Carbon::parse($item->batas_kembali)->format('d-m-Y') }}
    </td>

    <td>{{ ucfirst($item->status_pinjam) }}</td>

</tr>

@endforeach

</tbody>

</table>

{{-- ========================= PENGEMBALIAN ========================= --}}

@elseif($jenis == 'pengembalian')

<table>

<thead>

<tr>
    <th>No</th>
    <th>Tanggal Kembali</th>
    <th>Nama Anggota</th>
    <th>Alat Musik</th>
    <th>Kondisi Alat</th>
    <th>Admin</th>
</tr>

</thead>

<tbody>

@foreach($data as $item)

<tr>

    <td>{{ $loop->iteration }}</td>

    <td>
        {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') }}
    </td>

    <td>{{ $item->peminjaman->anggota->nama ?? '-' }}</td>

    <td>
        @if($item->detail && $item->detail->alat)
            {{ $item->detail->alat->nama_alat }}
        @elseif($item->peminjaman && $item->peminjaman->details && $item->peminjaman->details->count() > 0)
            {{ $item->peminjaman->details->map(fn($d) => $d->alat->nama_alat ?? '')->filter()->join(', ') }}
        @else
            {{ $item->peminjaman->alat->nama_alat ?? '-' }}
        @endif
    </td>

    <td>{{ $item->kondisi_alat }}</td>

    <td>{{ $item->admin?->nama_admin ?? '-' }}</td>

</tr>

@endforeach

</tbody>

</table>

{{-- ========================= PELANGGARAN ========================= --}}

@elseif($jenis == 'pelanggaran')

<table>

<thead>

<tr>

    <th>No</th>
    <th>Tanggal</th>
    <th>Nama Anggota</th>
    <th>Alat Musik</th>
    <th>Jenis Pelanggaran</th>
    <th>Hari Terlambat</th>
    <th>Denda Terlambat</th>
    <th>Denda Kerusakan</th>
    <th>Total Denda</th>
    <th>Sanksi</th>

</tr>

</thead>

<tbody>

@foreach($data as $item)

<tr>

    <td>{{ $loop->iteration }}</td>

    <td>
        {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d-m-Y') }}
    </td>

    <td>{{ $item->anggota->nama ?? '-' }}</td>

    <td>
        @if($item->pengembalian && $item->pengembalian->detail && $item->pengembalian->detail->alat)
            {{ $item->pengembalian->detail->alat->nama_alat }}
        @elseif($item->pengembalian && $item->pengembalian->peminjaman && $item->pengembalian->peminjaman->details && $item->pengembalian->peminjaman->details->count() > 0)
            {{ $item->pengembalian->peminjaman->details->map(fn($d) => $d->alat->nama_alat ?? '')->filter()->join(', ') }}
        @else
            {{ optional(optional(optional($item->pengembalian)->peminjaman)->alat)->nama_alat ?? '-' }}
        @endif
    </td>

    <td>{{ $item->jenis_pelanggaran }}</td>

    <td>{{ $item->hari_terlambat }}</td>

    <td>
        Rp {{ number_format($item->denda_keterlambatan,0,',','.') }}
    </td>

    <td>
        Rp {{ number_format($item->denda_kerusakan,0,',','.') }}
    </td>

    <td>
        Rp {{ number_format($item->denda_keterlambatan + $item->denda_kerusakan,0,',','.') }}
    </td>

    <td>{{ $item->sanksi }}</td>

</tr>

@endforeach

</tbody>

</table>

@endif

</body>

</html>