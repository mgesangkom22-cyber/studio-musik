<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode - {{ $alat->nama_alat }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .barcode-label {
            width: 320px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            border: 2px dashed #0f6d3b;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        .studio-header {
            font-size: 14px;
            font-weight: 700;
            color: #0f6d3b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .item-title {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
            margin-top: 8px;
            margin-bottom: 2px;
        }
        .item-category {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 12px;
        }
        .barcode-wrapper {
            display: inline-block;
            margin: 10px 0;
            padding: 10px;
            background: #fff;
        }
        .barcode-code {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 3px;
            color: #000;
            margin-top: 4px;
        }
        .action-buttons {
            margin-top: 20px;
            text-align: center;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            body {
                background-color: #ffffff;
            }
            .action-buttons {
                display: none !important;
            }
            .barcode-label {
                margin: 0 auto;
                box-shadow: none;
                border: 2px solid #000;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="action-buttons mb-4">
            <button onclick="window.print()" class="btn btn-success me-2">
                <i class="bi bi-printer-fill"></i> Cetak Now
            </button>
            <button onclick="window.close()" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Tutup
            </button>
        </div>

        <div class="barcode-label">
            <div class="studio-header">
                <i class="bi bi-music-note-beamed"></i> Studio Musik
            </div>
            
            <div class="item-title">{{ $alat->nama_alat }}</div>
            <div class="item-category">Kategori: {{ $alat->kategori }}</div>

            @php
                $code = $alat->barcode_alat ?? $alat->kode_alat;
            @endphp

            @if($code)
                <div class="barcode-wrapper">
                    {!! DNS1D::getBarcodeSVG($code, 'C128', 2, 60, 'black', false) !!}
                </div>
                <div class="barcode-code">{{ $code }}</div>
            @else
                <div class="text-danger my-3">Barcode tidak tersedia</div>
            @endif

            @if($alat->id_rfid_alat)
                <div class="mt-2 text-muted" style="font-size: 11px;">
                    RFID: {{ $alat->id_rfid_alat }}
                </div>
            @endif
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            setTimeout(() => {
                window.print();
            }, 400);
        });
    </script>
</body>
</html>
