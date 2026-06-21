@extends('layouts.staff')

@section('content')

<style>
    .page-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .search-box {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .search-box input {
        width: 320px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }

    .btn {
        padding: 10px 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
    }

    .btn-secondary {
        background: #e5e7eb;
        color: black;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
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

    tr:hover {
        background: #fafafa;
    }

    .status-dipinjam {
        background: #f59e0b;
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .status-denda {
        background: #dc2626;
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .empty-box {
        padding: 30px;
        text-align: center;
        color: #777;
    }
</style>


<div class="page-title">
    Daftar Eksemplar Keluar
</div>


<div class="card">

    <table>

        <tr>
            <th>KODE EKSEMPLAR</th>
            <th>JUDUL BUKU</th>
            <th>PEMINJAM</th>
            <th>TANGGAL PINJAM</th>
            <th>TANGGAL KEMBALI</th>
            <th>STATUS</th>
        </tr>

        @forelse($loans as $loan)

        <tr>

            <td>
                {{ $loan->kode_eksemplar }}
            </td>

            <td>
                {{ $loan->book->judul }}
            </td>

            <td>
                {{ $loan->user->name }}
            </td>

            <td>
                {{ $loan->tanggal_pinjam->format('d M Y') }}
            </td>

            <td>
                {{ $loan->tanggal_kembali->format('d M Y') }}
            </td>

            <td>

                @php
                $today = now()->startOfDay();

                $jatuhTempo = \Carbon\Carbon::parse(
                $loan->tanggal_kembali
                )->startOfDay();

                $kenaDenda =
                $loan->status == 'denda' ||
                $today->gt($jatuhTempo);
                @endphp

                @if($kenaDenda)

                <span class="status-denda">
                    Denda
                </span>

                @else

                <span class="status-dipinjam">
                    Dipinjam
                </span>

                @endif

            </td>

        </tr>

        @empty

        <tr>
            <td colspan="6">
                Tidak ada buku yang sedang dipinjam
            </td>
        </tr>

        @endforelse

    </table>

</div>

@endsection
