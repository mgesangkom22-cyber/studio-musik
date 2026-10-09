<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

<h2>Halo {{ $nama }}</h2>

<p>
Terima kasih telah melakukan pendaftaran sebagai anggota
Studio Musik Universitas Nahdlatul Ulama Yogyakarta.
</p>

<p>
Mohon maaf, setelah dilakukan proses verifikasi oleh Admin,
pendaftaran Anda belum dapat diterima.
</p>

<p><b>Catatan Admin :</b></p>

<div style="
background:#fff3cd;
padding:15px;
border-radius:8px;
border:1px solid #ffe69c;
">

{{ $catatan }}

</div>

<br>

<p>
Silakan melakukan perbaikan sesuai catatan di atas,
kemudian melakukan pendaftaran kembali.
</p>

<hr>

<p>
Salam,
<br>
Admin Studio Musik UNU Yogyakarta
</p>

</body>
</html>