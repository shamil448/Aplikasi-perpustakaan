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
</style>

<div class="page-title">
    Laporan Keuangan
</div>

@php
    $total = 0;
@endphp

@foreach($loans as $loan)
    @php
        $today = now()->startOfDay();
        $jatuhTempo = \Carbon\Carbon::parse($loan->tanggal_kembali)->startOfDay();

        $dendaTampil = $loan->denda_dibayar ?? 0;

        if ($dendaTampil <= 0 && $today->gt($jatuhTempo)) {
            $telat = $jatuhTempo->diffInDays($today);
            $dendaTampil = $telat * 1000;
        }

        $total += $dendaTampil;
    @endphp
@endforeach

<div class="total-box">
    Total Denda Lunas: Rp {{ number_format($total, 0, ',', '.') }}
</div>

<div class="card">

    <table>
        <tr>
            <th>Nama Peminjam</th>
            <th>Nama Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Jumlah Denda</th>
            <th>Status</th>
        </tr>

        @forelse($loans as $loan)

            @php
                $today = now()->startOfDay();
                $jatuhTempo = \Carbon\Carbon::parse($loan->tanggal_kembali)->startOfDay();

                $dendaTampil = $loan->denda_dibayar ?? 0;

                if ($dendaTampil <= 0 && $today->gt($jatuhTempo)) {
                    $telat = $jatuhTempo->diffInDays($today);
                    $dendaTampil = $telat * 1000;
                }
            @endphp

            <tr>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->judul }}</td>
                <td>{{ $loan->tanggal_pinjam->format('d M Y') }}</td>
                <td>{{ $loan->tanggal_kembali->format('d M Y') }}</td>
                <td style="color:#16a34a;font-weight:600;">
                    Rp {{ number_format($dendaTampil, 0, ',', '.') }}
                </td>
                <td>
                    <span class="status-lunas">Lunas</span>
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="6" class="empty">
                    Belum ada pembayaran denda yang lunas
                </td>
            </tr>

        @endforelse
    </table>

</div>

@endsection
