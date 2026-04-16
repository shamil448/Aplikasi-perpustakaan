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
            <option value="all">Semua Ruas</option>
            <option value="judul">Judul</option>
            <option value="pengarang">Pengarang</option>
            <option value="isbn_issn">ISBN</option>
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

                        </div>

                    </div>

                </td>

                <td>
                    {{ $book->isbn_issn }}
                </td>

                {{-- STATUS --}}
                <td>
                    @php
                    $dipinjam = \App\Models\Loan::where('kode_eksemplar', $book->eksemplar)
                    ->where('status', 'dipinjam')
                    ->exists();
                    @endphp

                    @if($dipinjam)
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
