<!DOCTYPE html>
<html>

<head>
    <title>Daftar Buku</title>

    <style>
        body {
            margin: 0;
            font-family: Segoe UI, Arial;
            background: #f3f4f6;
        }

        .container {
            width: 90%;
            margin: 40px auto;
        }

        .title {
            font-size: 34px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .search-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-input {
            padding: 10px;
            width: 320px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .search-button:hover {
            background: #1d4ed8;
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #eee;
            color: #555;
            font-size: 15px;
        }

        td {
            padding: 18px 14px;
            border-bottom: 1px solid #f1f1f1;
            vertical-align: middle;
        }

        tr:hover {
            background: #fafafa;
        }

        .book-cover {
            width: 70px;
            height: 95px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .book-title {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 4px;
        }

        .book-author {
            font-size: 13px;
            color: #666;
        }

        .status-available {
            background: #22c55e;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
        }

        .status-borrowed {
            background: #ef4444;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
        }

        .eksemplar-box {
            background: #eff6ff;
            padding: 7px 12px;
            border-radius: 6px;
            display: inline-block;
            font-size: 13px;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
            font-size: 15px;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="title">
            Daftar Buku Perpustakaan
        </div>

        {{-- SEARCH --}}
        <form method="GET" action="/daftar-buku" class="search-box">

            <div class="search-wrapper">

                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Cari buku..."
                    value="{{ request('search') }}">

                <select
                    name="filter"
                    class="search-select">

                    <option value="all">Semua</option>

                    <option
                        value="judul"
                        {{ request('filter') == 'judul' ? 'selected' : '' }}>
                        Judul
                    </option>

                    <option
                        value="pengarang"
                        {{ request('filter') == 'pengarang' ? 'selected' : '' }}>
                        Pengarang
                    </option>

                    <option
                        value="isbn"
                        {{ request('filter') == 'isbn' ? 'selected' : '' }}>
                        ISBN
                    </option>

                </select>

                <button
                    type="submit"
                    class="search-button">

                    Cari

                </button>

            </div>

        </form>

        <div class="card">

            <table>

                <tr>
                    <th width="100">Cover</th>
                    <th>Judul</th>
                    <th width="220">ISBN / ISSN</th>
                    <th width="180">Status</th>
                    <th width="180">Eksemplar</th>
                </tr>

                @forelse($books as $book)

                <tr>

                    {{-- COVER --}}
                    <td>

                        @if($book->gambar)

                        <img
                            src="{{ asset('storage/'.$book->gambar) }}"
                            class="book-cover">

                        @else

                        <img
                            src="https://via.placeholder.com/70x95"
                            class="book-cover">

                        @endif

                    </td>

                    {{-- JUDUL --}}
                    <td>

                        <div class="book-title">
                            {{ $book->judul }}
                        </div>

                        <div class="book-author">
                            {{ $book->pengarang }}
                        </div>

                    </td>

                    {{-- ISBN --}}
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

                        <span class="status-borrowed">
                            Dipinjam
                        </span>

                        @else

                        <span class="status-available">
                            Tersedia
                        </span>

                        @endif

                    </td>

                    {{-- EKSEMPLAR --}}
                    <td>

                        @if($book->eksemplar)

                        <div class="eksemplar-box">
                            {{ $book->eksemplar }}
                        </div>

                        @else

                        -

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="empty">
                        Buku tidak ditemukan
                    </td>

                </tr>

                @endforelse

            </table>

        </div>

    </div>

</body>

</html>
