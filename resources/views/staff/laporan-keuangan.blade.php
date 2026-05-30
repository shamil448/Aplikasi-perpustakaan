@extends('layouts.staff')

@section('content')

<style>
    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        padding: 12px;
        border-bottom: 1px solid #eee;
        color: #666;
        font-size: 13px;
    }

    td {
        padding: 14px 12px;
        border-bottom: 1px solid #f3f3f3;
    }

    .status-lunas {
        background: #16a34a;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .total-box {
        background: #dcfce7;
        color: #166534;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .empty {
        text-align: center;
        color: #777;
        padding: 25px;
    }

    .toolbar {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .btn-pdf {
        display: inline-block;
        background: #dc2626;
        color: white;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-pdf:hover {
        background: #b91c1c;
    }

    .btn-excel {
        display: inline-block;
        background: #16a34a;
        color: white;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-excel:hover {
        background: #15803d;
    }
</style>

<div class="page-title">
    Laporan Keuangan
</div>

@php
    $total = $loans->sum('denda_dibayar');
@endphp

<div class="toolbar">

    <a href="/staff/laporan-keuangan/pdf"
       class="btn-pdf">
        📄 Cetak PDF
    </a>

    <a href="/staff/laporan-keuangan/excel"
       class="btn-excel">
        📊 Export Excel
    </a>

</div>

<div class="total-box">
    Total Denda Lunas:
    Rp {{ number_format($total, 0, ',', '.') }}
</div>

<div class="card">

    <table>

        <tr>
            <th>Nama Peminjam</th>
            <th>Nama Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Tanggal Bayar</th>
            <th>Jumlah Denda Dibayar</th>
            <th>Status</th>
        </tr>

        @forelse($loans as $loan)

            <tr>

                <td>{{ $loan->user->name }}</td>

                <td>{{ $loan->book->judul }}</td>

                <td>
                    {{ $loan->tanggal_pinjam->format('d M Y') }}
                </td>

                <td>
                    {{ $loan->tanggal_kembali->format('d M Y') }}
                </td>

                <td>
                    {{ $loan->tanggal_bayar
                        ? $loan->tanggal_bayar->format('d M Y H:i')
                        : '-' }}
                </td>

                <td style="color:#16a34a;font-weight:600;">
                    Rp {{ number_format(
                        $loan->denda_dibayar,
                        0,
                        ',',
                        '.'
                    ) }}
                </td>

                <td>
                    <span class="status-lunas">
                        Lunas
                    </span>
                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7" class="empty">
                    Belum ada pembayaran denda yang lunas
                </td>

            </tr>

        @endforelse

    </table>

</div>

@endsection
