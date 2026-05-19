<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kontrak Kemitraan Reseller</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        margin: 40px;
    }

    .header {
        text-align: center;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
        margin-bottom: 30px;
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }

    .content {
        text-align: justify;
    }

    .party-info {
        margin: 20px 0;
        padding: 15px;
        background-color: #f9f9f9;
        border-left: 4px solid #4f46e5;
    }

    .footer {
        margin-top: 50px;
    }

    .signature-box {
        float: right;
        text-align: center;
        width: 200px;
    }

    .signature-line {
        margin-top: 80px;
        border-bottom: 1px solid #000;
    }
    </style>
</head>

<body>

    <div class="header">
        <h1 class="title">SURAT PERJANJIAN KEMITRAAN RESELLER</h1>
        <p>No: CTR-{{ date('Y') }}-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="content">
        <p>Pada hari ini, tanggal <strong>{{ date('d F Y') }}</strong>, telah disepakati perjanjian kemitraan antara
            Satiraksa Store (Pihak Pertama) dan:</p>

        <div class="party-info">
            <strong>Nama Lengkap:</strong> {{ $user->name }}<br>
            <strong>Email Terdaftar:</strong> {{ $user->email }}<br>
            <strong>Status Kemitraan:</strong> Reseller Aktif
        </div>

        <p>Dengan ini (Pihak Kedua) setuju untuk mematuhi seluruh syarat dan ketentuan penjualan produk yang berlaku di
            lingkungan Satiraksa Store, mencakup namun tidak terbatas pada aturan harga batas bawah, etika pemasaran,
            dan ketentuan garansi retur produk.</p>

        <p>Perjanjian ini dibuat secara sadar dan dihasilkan oleh sistem secara otomatis. Dokumen ini sah sebagai bukti
            kemitraan Anda.</p>
    </div>

    <div class="footer">
        <div class="signature-box">
            <p>Disetujui Oleh,</p>
            <div class="signature-line"></div>
            <p><strong>{{ $user->name }}</strong></p>
        </div>
    </div>

</body>

</html>