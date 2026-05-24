@extends('layouts.staff')

@section('content')

<style>
    .page-title {
        font-size: 22px;
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
        font-size: 13px;
        color: #666;
    }

    td {
        padding: 14px 12px;
        border-bottom: 1px solid #f3f3f3;
    }

    .status-denda {
        background: #dc2626;
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .empty {
        text-align: center;
        padding: 25px;
        color: #777;
    }
</style>

<div class="page-title">
    Daftar Eksemplar Denda
</div>

<div class="card">

    <table>
        <tr>
            <th>Nama Peminjam</th>
            <th>Nama Buku</th>
            <th>Kode Eksemplar</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Jumlah Denda</th>
            <th>Status</th>
        </tr>

        @forelse($loans as $loan)

            @php
                $today = now()->startOfDay();
                $jatuhTempo = \Carbon\Carbon::parse($loan->tanggal_kembali)->startOfDay();

                $telat = $jatuhTempo->diffInDays($today);
                $denda = $telat * 1000;
            @endphp

            <tr>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->judul }}</td>
                <td>{{ $loan->kode_eksemplar }}</td>
                <td>{{ $loan->tanggal_pinjam->format('d M Y') }}</td>
                <td>{{ $loan->tanggal_kembali->format('d M Y') }}</td>
                <td style="color:#dc2626;font-weight:600;">
                    Rp {{ number_format($denda, 0, ',', '.') }}
                </td>
                <td>
                    <span class="status-denda">Denda</span>
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="7" class="empty">
                    Tidak ada eksemplar yang terkena denda
                </td>
            </tr>

        @endforelse
    </table>

</div>

@endsection
