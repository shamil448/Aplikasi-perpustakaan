@extends('layouts.staff')

@section('content')

<div class="page-title">
    Informasi Buku
</div>

<div class="card">

    <h3>{{ $book->judul }}</h3>

    <table style="margin-bottom:20px">

        <tr>
            <td width="150"><b>Pengarang</b></td>
            <td>{{ $book->pengarang }}</td>
        </tr>

        <tr>
            <td><b>ISBN / ISSN</b></td>
            <td>{{ $book->isbn_issn }}</td>
        </tr>

        <tr>
            <td><b>Tahun Terbit</b></td>
            <td>{{ $book->tahun_terbit }}</td>
        </tr>

        <tr>
            <td><b>No Panggil</b></td>
            <td>{{ $book->no_panggil }}</td>
        </tr>

        <tr>
            <td><b>Lokasi</b></td>
            <td>{{ $book->lokasi }}</td>
        </tr>

        <tr>
            <td><b>Lokasi Rak</b></td>
            <td>{{ $book->lokasi_rak }}</td>
        </tr>

    </table>

    <hr>

    <h4>Data Eksemplar</h4>

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
