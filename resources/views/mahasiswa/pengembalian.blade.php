@extends('layouts.mahasiswa')

@section('content')

    <style>
        .wrapper {
            display: flex;
            gap: 30px;
        }

        .sirkulasi {
            width: 320px;
            background: linear-gradient(180deg, #1e40af, #2563eb);
            padding: 30px;
            border-radius: 15px;
            color: white;
        }

        .sirkulasi h3 {
            margin-bottom: 20px;
            font-size: 14px;
            opacity: .8;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .menu a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            padding: 12px 18px;
            border-radius: 30px;
        }

        .menu a.active {
            background: white;
            color: #2563eb;
            font-weight: 600;
        }

        .transaksi {
            flex: 1;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
        }

        .tabs {
            display: flex;
            gap: 25px;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .tabs a {
            text-decoration: none;
            color: #444;
            font-weight: 500;
        }

        .tabs a.active {
            color: #2563eb;
            font-weight: 600;
        }

        .info-denda {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 22px;
            padding: 14px 18px;
            background: #fff8e1;
            border-left: 5px solid #f59e0b;
            border-radius: 8px;
        }

        .info-denda .icon {
            font-size: 22px;
        }

        .info-denda .text {
            color: #92400e;
            line-height: 1.6;
            font-size: 14px;
        }

        .info-denda .text strong {
            display: block;
            margin-bottom: 4px;
            color: #78350f;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: white;
        }

        .btn-blue {
            background: #2563eb;
        }

        .card {
            margin-top: 25px;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 20px;
        }

        .row {
            margin-bottom: 12px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }
    </style>

    <div class="wrapper">

        <div class="sirkulasi">

            <h3>SIRKULASI</h3>

            <div class="menu">

                <a href="/mahasiswa/sirkulasi">
                    Mulai Transaksi
                </a>

                <a href="/mahasiswa/sejarah">
                    Sejarah Peminjaman
                </a>

            </div>

        </div>

        <div class="transaksi">

            <div class="info-denda">

                <div class="icon">
                    📢
                </div>

                <div class="text">

                    <strong>Informasi Perhitungan Denda</strong>

                    Denda keterlambatan hanya dihitung pada <b>hari kerja</b>.
                    Hari <b>Sabtu</b>, <b>Minggu</b>, dan <b>hari libur resmi perpustakaan</b>
                    tidak diperhitungkan sebagai hari keterlambatan sehingga
                    <b>tidak menambah nominal denda</b>.

                </div>

            </div>

            <div class="tabs">

                <a href="/mahasiswa/sirkulasi">
                    Peminjaman (F2)
                </a>

                <a href="/mahasiswa/pinjaman">
                    Pinjaman Saat Ini (F3)
                </a>

                <a href="/mahasiswa/pengembalian" class="active">

                    Pengembalian (F4)

                </a>

                <a href="/mahasiswa/denda">
                    Denda (F9)
                </a>

                <a href="/mahasiswa/sejarah">
                    Sejarah Peminjaman (F10)
                </a>

            </div>

            @if(session('success'))

                <div class="alert-success">
                    {{ session('success') }}
                </div>

            @endif

            @if(session('error'))

                <div class="alert-error">
                    {{ session('error') }}
                </div>

            @endif

            <h3 style="margin-bottom:20px;">
                Pengembalian Buku
            </h3>

            <form method="POST" action="/mahasiswa/pengembalian">

                @csrf

                <input type="text" name="kode" class="form-control" placeholder="Masukkan kode eksemplar">

                <button type="submit" class="btn btn-blue">

                    Cari Buku

                </button>

            </form>

            @isset($loan)

                <div class="card">

                    <div class="row">
                        <span class="label">Kode Eksemplar :</span>
                        {{ $loan->kode_eksemplar }}
                    </div>

                    <div class="row">
                        <span class="label">Judul Buku :</span>
                        {{ $loan->book->judul }}
                    </div>

                    <div class="row">
                        <span class="label">Tanggal Pinjam :</span>
                        {{ $loan->tanggal_pinjam->format('d M Y') }}
                    </div>

                    <div class="row">
                        <span class="label">Tanggal Kembali :</span>
                        {{ $loan->tanggal_kembali->format('d M Y') }}
                    </div>

                    <form method="POST" action="/mahasiswa/pengembalian/{{ $loan->id }}"
                        onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')">

                        @csrf

                        <button type="submit" class="btn btn-blue">

                            Konfirmasi Pengembalian

                        </button>

                    </form>

                </div>

            @endisset

        </div>

    </div>

@endsection