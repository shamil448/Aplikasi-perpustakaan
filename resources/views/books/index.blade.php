@extends('layouts.staff')

@section('content')

<style>
    .status-available {
        background: #28a745;
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .status-empty {
        background: #dc3545;
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .status-denda {
        background: #f59e0b;
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .kategori-badge {
        background: #e0e7ff;
        color: #3730a3;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
        margin-top: 6px;
        text-transform: capitalize;
    }

    .sinopsis-text {
        font-size: 12px;
        color: #666;
        margin-top: 6px;
        max-width: 420px;
        line-height: 1.4;
    }
</style>

<div class="page-header">

    <div class="page-title">
        Bibliografi
    </div>

    <div class="top-buttons">
        <a href="/books" class="btn btn-gray">Daftar Bibliografi</a>
        <a href="/books/create" class="btn btn-gray">Tambah Bibliografi Baru</a>
    </div>

</div>


<form method="GET" action="/books">

    <div class="search-bar">

        <label>Cari</label>

        <input
            class="search-input"
            type="text"
            name="search"
            placeholder="Cari buku..."
            value="{{ request('search') }}">

        <select class="search-select" name="field">
            <option value="all" {{ request('field') == 'all' ? 'selected' : '' }}>Semua Ruas</option>
            <option value="judul" {{ request('field') == 'judul' ? 'selected' : '' }}>Judul</option>
            <option value="pengarang" {{ request('field') == 'pengarang' ? 'selected' : '' }}>Pengarang</option>
            <option value="isbn_issn" {{ request('field') == 'isbn_issn' ? 'selected' : '' }}>ISBN</option>
            <option value="kategori" {{ request('field') == 'kategori' ? 'selected' : '' }}>Kategori</option>
        </select>

        <button class="btn btn-gray" type="submit">
            Cari
        </button>

        <button class="btn btn-blue" type="button">
            Pencarian Spesifik
        </button>

    </div>

</form>


<form method="POST" action="/books/delete-selected">

    @csrf

    <div style="margin-bottom:15px; display:flex; gap:10px;">

        <button type="submit" class="btn btn-danger">
            Hapus Terpilih
        </button>

        <button type="button" onclick="selectAll()" class="btn btn-secondary">
            Tandai Semua
        </button>

        <button type="button" onclick="unselectAll()" class="btn btn-secondary">
            Hilangkan Semua
        </button>

    </div>


    <div class="card">

        <table>

            <tr>
                <th width="40">HAPUS</th>
                <th width="120">AKSI</th>
                <th>JUDUL</th>
                <th width="140">KATEGORI</th>
                <th width="150">ISBN / ISSN</th>
                <th width="120">STATUS</th>
                <th width="180">EKSEMPLAR</th>
            </tr>

            @foreach($books as $book)

            <tr>

                <td>
                    <input type="checkbox" class="book-checkbox" name="ids[]" value="{{ $book->id }}">
                </td>

                <td>

                    <div class="action-buttons">

                        <a href="/books/{{ $book->id }}/edit" class="btn btn-secondary">
                            ✏️
                        </a>

                        <form action="/books/{{ $book->id }}" method="POST">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-danger"
                                onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                🗑
                            </button>

                        </form>

                    </div>

                </td>

                <td>

                    <div style="display:flex;gap:15px;align-items:center;">

                        @if($book->gambar)

                            <img class="book-cover" src="{{ asset('storage/'.$book->gambar) }}">

                        @else

                            <img class="book-cover" src="https://via.placeholder.com/60x80">

                        @endif

                        <div>

                            <div class="book-title">
                                {{ $book->judul }}
                            </div>

                            <div class="book-author">
                                {{ $book->pengarang }}
                            </div>

                            <div class="book-meta">
                                {{ $book->tempat_terbit }} - {{ $book->tahun_terbit }}
                            </div>

                            <div class="sinopsis-text">
                                {{ $book->sinopsis ? Str::limit($book->sinopsis, 120) : 'Sinopsis belum tersedia' }}
                            </div>

                        </div>

                    </div>

                </td>

                <td>
                    @if($book->kategori)
                        <span class="kategori-badge">
                            {{ $book->kategori }}
                        </span>
                    @else
                        -
                    @endif
                </td>

                <td>
                    {{ $book->isbn_issn }}
                </td>

                {{-- STATUS --}}
                <td>
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

                    @if($status == 'denda')
                        <span class="status-denda">Denda</span>
                    @elseif($status == 'dipinjam')
                        <span class="status-empty">Dipinjam</span>
                    @else
                        <span class="status-available">Tersedia</span>
                    @endif
                </td>

                <td>

                    <div class="eksemplar-box">

                        <div class="eksemplar-label">
                            Kelola Eksemplar
                        </div>

                        <a href="/books/{{ $book->id }}/eksemplar" class="btn btn-primary">
                            Eksemplar
                        </a>

                    </div>

                </td>

            </tr>

            @endforeach

        </table>

    </div>

</form>


<script>
    function selectAll() {
        document.querySelectorAll('.book-checkbox').forEach(cb => cb.checked = true);
    }

    function unselectAll() {
        document.querySelectorAll('.book-checkbox').forEach(cb => cb.checked = false);
    }
</script>

@endsection
