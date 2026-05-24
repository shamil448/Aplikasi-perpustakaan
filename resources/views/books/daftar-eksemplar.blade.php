@extends('layouts.staff')

@section('content')

<style>
    .page-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .search-box {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
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

    .status-available {
        background: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .status-dipinjam {
        background: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .status-denda {
        background: #f59e0b;
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
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

    tr:hover {
        background: #fafafa;
    }
</style>


<div class="page-title">
    Daftar Eksemplar
</div>


<form method="GET" action="/eksemplar">

    <div class="search-box">

        <input
            type="text"
            name="search"
            placeholder="Cari kode eksemplar..."
            value="{{ request('search') }}">

        <button class="btn btn-primary" type="submit">
            Cari
        </button>

    </div>

</form>


<div class="card">

    <table>

        <tr>
            <th width="120">SUNTING</th>
            <th>KODE EKSEMPLAR</th>
            <th>JUDUL BUKU</th>
            <th width="180">ISBN / ISSN</th>
            <th width="180">LOKASI</th>
            <th width="140">STATUS</th>
        </tr>

        @forelse($books as $book)

        @php
            $loan = \App\Models\Loan::where('kode_eksemplar', $book->eksemplar)
                ->whereIn('status', ['dipinjam', 'denda'])
                ->latest()
                ->first();

            $status = 'tersedia';

            if ($loan) {
                $today = now()->startOfDay();
                $jatuhTempo = \Carbon\Carbon::parse($loan->tanggal_kembali)->startOfDay();

                if ($loan->status == 'denda' || $today->gt($jatuhTempo)) {
                    $status = 'denda';
                } else {
                    $status = 'dipinjam';
                }
            }
        @endphp

        <tr>

            <td>
                <a href="/books/{{ $book->id }}/edit"
                   class="btn btn-secondary">
                    ✏️ Sunting
                </a>
            </td>

            <td>
                {{ $book->eksemplar ?: '-' }}
            </td>

            <td>
                {{ $book->judul }}
            </td>

            <td>
                {{ $book->isbn_issn }}
            </td>

            <td>
                {{ $book->lokasi_rak ?: $book->lokasi }}
            </td>

            <td>
                @if($status == 'denda')
                    <span class="status-denda">Denda</span>
                @elseif($status == 'dipinjam')
                    <span class="status-dipinjam">Dipinjam</span>
                @else
                    <span class="status-available">Tersedia</span>
                @endif
            </td>

        </tr>

        @empty

        <tr>
            <td colspan="6">
                Data eksemplar belum tersedia
            </td>
        </tr>

        @endforelse

    </table>

</div>

@endsection
