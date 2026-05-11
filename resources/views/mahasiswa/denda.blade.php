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

    /* ================= TAB FIX ================= */
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
        position: relative;
        transition: .2s;
    }

    .tabs a:hover {
        color: #2563eb;
    }

    .tabs a.active {
        color: #2563eb;
        font-weight: 600;
    }

    .tabs a.active::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 100%;
        height: 2px;
        background: #2563eb;
        border-radius: 2px;
    }

    /* =========================================== */

    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }

    .table-modern th,
    .table-modern td {
        padding: 12px 10px;
        border-bottom: 1px solid #eee;
    }

    .table-modern tr:hover {
        background: #f9fafb;
    }

    .empty {
        text-align: center;
        padding: 20px;
        color: #888;
    }

    .btn-red {
        background: #dc2626;
        color: white;
        padding: 6px 10px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
    }
</style>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sirkulasi">
        <h3>SIRKULASI</h3>
        <div class="menu">
            <a href="/mahasiswa/sirkulasi">Mulai Transaksi</a>
            <a>Pengembalian Kilat</a>
            <a>Aturan Peminjaman</a>
            <a>Sejarah Peminjaman</a>
            <a>Peringatan Jatuh Tempo</a>
            <a class="active">Daftar Keterlambatan</a>
            <a>Reservasi</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="transaksi">

        <div class="tabs">
            <a href="/mahasiswa/sirkulasi">Peminjaman (F2)</a>
            <a href="/mahasiswa/pinjaman">Pinjaman Saat Ini (F3)</a>
            <a>Reservasi (F4)</a>
            <a class="active">Denda (F9)</a>
            <a>Sejarah Peminjaman (F10)</a>
        </div>

        <h3 style="margin-bottom:20px;">Daftar Denda</h3>

        <table class="table-modern">
            <tr>
                <th>Judul</th>
                <th>Jatuh Tempo</th>
                <th>Telat</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>

            @forelse($loans as $loan)

            @php
            $today = now();
            $jatuhTempo = $loan->tanggal_kembali;

            $telat = 0;
            $denda = 0;

            if ($today > $jatuhTempo) {
            $telat = $jatuhTempo->diffInDays($today, false);
            $telat = abs((int)$telat);
            }
            @endphp

            @if($denda > 0)
            <tr>
                <td>{{ $loan->book->judul }}</td>
                <td>{{ $loan->tanggal_kembali->format('d M Y') }}</td>
                <td>{{ $telat }} hari</td>
                <td style="color:#dc2626;font-weight:600;">
                    Rp {{ number_format($denda) }}
                </td>
                <td>
                    <a href="{{ route('bayar', $loan->id) }}" class="btn-red">
                        Bayar
                    </a>
                </td>
            </tr>
            @endif

            @empty
            <tr>
                <td colspan="5" class="empty">
                    Tidak ada denda
                </td>
            </tr>
            @endforelse

        </table>

    </div>

</div>

@endsection
