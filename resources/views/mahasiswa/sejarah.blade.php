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
        position: relative;
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
    }

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

    .status-kembali {
        color: #16a34a;
        font-weight: 600;
    }

    .status-lunas {
        color: #2563eb;
        font-weight: 600;
    }

    .empty {
        text-align: center;
        padding: 20px;
        color: #888;
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
            <a href="/mahasiswa/pinjaman">Pinjaman Saat Ini (F3)</a>
            <a>Reservasi (F4)</a>
            <a href="/mahasiswa/denda">Denda (F9)</a>
            <a class="active">Sejarah Peminjaman (F10)</a>
        </div>

        <h3 style="margin-bottom:20px;">Sejarah Peminjaman</h3>

        <table class="table-modern">

            <tr>
                <th>Nama</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>

            @forelse($loans as $loan)
            <tr>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->judul }}</td>
                <td>{{ $loan->tanggal_pinjam->format('d M Y') }}</td>
                <td>{{ $loan->tanggal_kembali->format('d M Y') }}</td>

                <td>
                    @if($loan->status == 'lunas')
                    <span class="status-lunas">Lunas</span>
                    @else
                    <span class="status-kembali">Kembali</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="empty">
                    Belum ada riwayat peminjaman
                </td>
            </tr>
            @endforelse

        </table>

    </div>

</div>

@endsection
