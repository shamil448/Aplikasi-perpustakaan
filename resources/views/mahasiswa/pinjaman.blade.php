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

    /* ================= TAB ================= */
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
        transition: 0.2s;
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

    /* BADGE NOTIF */
    .badge {
        background: #dc2626;
        color: white;
        font-size: 11px;
        padding: 3px 7px;
        border-radius: 50px;
        margin-left: 6px;
        font-weight: 600;
    }

    /* ================= TABLE ================= */
    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }

    .table-modern th {
        text-align: left;
        font-size: 14px;
        color: #666;
        padding: 10px;
        border-bottom: 2px solid #eee;
    }

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

    .btn {
        padding: 6px 10px;
        border: none;
        border-radius: 6px;
        color: white;
        cursor: pointer;
        font-size: 12px;
        display: inline-block;
        text-decoration: none;
    }

    .btn-green {
        background: #16a34a;
    }

    .btn-red {
        background: #dc2626;
    }
</style>

@php
$dendaCount = 0;

foreach ($loans as $loan) {
if (now() > $loan->tanggal_kembali) {
$dendaCount++;
}
}
@endphp

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sirkulasi">
        <h3>SIRKULASI</h3>
        <div class="menu">
            <a href="/mahasiswa/sirkulasi">Mulai Transaksi</a>
            <a>Pengembalian Kilat</a>
            <a>Aturan Peminjaman</a>
            <a class="active">Sejarah Peminjaman</a>
            <a>Peringatan Jatuh Tempo</a>
            <a>Daftar Keterlambatan</a>
            <a>Reservasi</a>
        </div>
    </div>

    <!-- KONTEN -->
    <div class="transaksi">

        <div class="tabs">
            <a href="/mahasiswa/sirkulasi">Peminjaman (F2)</a>
            <a class="active">Pinjaman Saat Ini (F3)</a>
            <a>Reservasi (F4)</a>

            <a href="/mahasiswa/denda">
                Denda (F9)
                @if($dendaCount > 0)
                <span class="badge">{{ $dendaCount }}</span>
                @endif
            </a>

            <a>Sejarah Peminjaman (F10)</a>
        </div>

        <h3 style="margin-bottom:20px;">Pinjaman Saat Ini</h3>

        <table class="table-modern">

            <tr>
                <th>Kode</th>
                <th>Judul</th>
                <th>Pinjam</th>
                <th>Kembali</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>

            @forelse($loans as $loan)

            @php
            $today = now();
            $jatuhTempo = $loan->tanggal_kembali;

            $denda = 0;

            if ($today > $jatuhTempo) {
            $telatHari = $jatuhTempo->diffInDays($today);
            $denda = $telatHari * 1000;
            }
            @endphp

            <tr>
                <td>{{ $loan->kode_eksemplar }}</td>
                <td>{{ $loan->book->judul }}</td>
                <td>{{ $loan->tanggal_pinjam->format('d M Y') }}</td>
                <td>{{ $loan->tanggal_kembali->format('d M Y') }}</td>

                <td>
                    @if($denda > 0)
                    <span style="color:#dc2626;font-weight:600;">
                        Rp {{ number_format($denda) }}
                    </span>
                    @else
                    -
                    @endif
                </td>

                <td>

                    @if(!$loan->is_extended && $today->diffInDays($jatuhTempo, false) == 1)
                    <form method="POST" action="/mahasiswa/perpanjang/{{ $loan->id }}">
                        @csrf
                        <button class="btn btn-green">
                            Perpanjang
                        </button>
                    </form>
                    @endif

                    @if($today > $jatuhTempo)
                    <form method="POST" action="/mahasiswa/aktivasi-denda/{{ $loan->id }}">
                        @csrf
                        <button class="btn btn-red">
                            Aktivasi Denda
                        </button>
                    </form>
                    @endif

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="6" class="empty">
                    Tidak ada buku yang sedang dipinjam
                </td>
            </tr>
            @endforelse

        </table>

    </div>

</div>

@endsection
