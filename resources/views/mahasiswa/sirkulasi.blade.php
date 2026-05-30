@extends('layouts.mahasiswa')

@section('content')

@php

use App\Models\Loan;

$totalDendaAktif = Loan::where('user_id', auth()->id())
->where(function ($query) {

$query->where('status', 'denda')

->orWhere(function ($q) {

$q->where('status', 'dipinjam')
->whereDate(
'tanggal_kembali',
'<',
    now()->toDateString()
    );
    });
    })
    ->sum('denda');

    $punyaDendaAktif = $totalDendaAktif > 0;

    @endphp

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
            border-bottom: 1px solid #ddd;
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

        .form-pinjam {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-pinjam input {
            padding: 10px;
            width: 400px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .form-pinjam button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .hasil {
            margin-top: 25px;
            padding: 15px;
            background: #f3f4f6;
            border-radius: 8px;
        }

        .alert-denda {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            font-weight: 600;
        }

        .btn-disabled {
            background: #9ca3af !important;
            cursor: not-allowed !important;
        }
    </style>

    <div class="wrapper">

        <div class="sirkulasi">

            <h3>SIRKULASI</h3>

            <div class="menu">

                <a class="active">Mulai Transaksi</a>
                <a>Pengembalian Kilat</a>
                <a>Aturan Peminjaman</a>
                <a>Sejarah Peminjaman</a>
                <a>Peringatan Jatuh Tempo</a>
                <a>Daftar Keterlambatan</a>
                <a>Reservasi</a>

            </div>

        </div>

        <div class="transaksi">

            <div class="tabs">

                <a class="active">Peminjaman (F2)</a>
                <a href="/mahasiswa/pinjaman">Pinjaman Saat Ini (F3)</a>
                <a>Reservasi (F4)</a>
                <a href="/mahasiswa/denda">Denda (F9)</a>
                <a href="/mahasiswa/sejarah">Sejarah Peminjaman (F10)</a>

            </div>

            @if($punyaDendaAktif)

            <div class="alert-denda">

                🚫 Anda masih memiliki denda aktif.

                <br><br>

                Total denda saat ini :

                <b>
                    Rp {{ number_format($totalDendaAktif, 0, ',', '.') }}
                </b>

                <br><br>

                Silakan lunasi denda terlebih dahulu sebelum melakukan peminjaman buku baru.

            </div>

            @endif

            <form method="POST" action="/mahasiswa/pinjam" class="form-pinjam">

                @csrf

                <label>Masukkan Kode Eksemplar/Barkod</label>

                <input
                    type="text"
                    name="kode"
                    placeholder="Contoh: MBR-001"
                    required
                    {{ $punyaDendaAktif ? 'disabled' : '' }}>

                <button
                    type="submit"
                    class="{{ $punyaDendaAktif ? 'btn-disabled' : '' }}"
                    {{ $punyaDendaAktif ? 'disabled' : '' }}>
                    Pinjam
                </button>

            </form>

            @if(session('error'))

            <div style="color:red;margin-top:20px;font-weight:600;">
                {{ session('error') }}
            </div>

            @endif

            @if(session('judul'))

            <div class="hasil">

                <p>
                    <b>Kode Eksemplar:</b>
                    {{ session('kode') }}
                </p>

                <p>
                    <b>Judul Buku:</b>
                    {{ session('judul') }}
                </p>

                <p>
                    <b>Tanggal Pinjam:</b>
                    {{ session('tanggal_pinjam') }}
                </p>

                <p>
                    <b>Tanggal Kembali:</b>
                    {{ session('tanggal_kembali') }}
                </p>

            </div>

            @endif

        </div>

    </div>

    @endsection
