@extends('layouts.staff')

@section('content')

<div class="page-title">
    Eksemplar Buku
</div>

<div class="card">

    <h3>{{ $book->judul }}</h3>

    <p><b>Pengarang:</b> {{ $book->pengarang }}</p>
    <p><b>ISBN:</b> {{ $book->isbn_issn }}</p>
    <p><b>Tahun:</b> {{ $book->tahun_terbit }}</p>

    <hr>

    <h4>Tambah Eksemplar</h4>

    <form method="POST" action="/books/{{ $book->id }}/eksemplar">

        @csrf

        <div class="form-group">
            <label>Kode Eksemplar</label>
            <input type="text" name="eksemplar" placeholder="Contoh: MBR-001">
        </div>

        <button class="btn btn-primary" type="submit">
            Simpan
        </button>

    </form>

    <hr>

    <h4>Daftar Eksemplar</h4>

    <table>

        <tr>
            <th width="50">No</th>
            <th>Kode Eksemplar</th>
        </tr>

        @if($book->eksemplar)

        <tr>
            <td>1</td>
            <td>{{ $book->eksemplar }}</td>
        </tr>

        @else

        <tr>
            <td colspan="2">Belum ada eksemplar</td>
        </tr>

        @endif

    </table>

</div>

@endsection
