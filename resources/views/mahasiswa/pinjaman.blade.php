@extends('layouts.mahasiswa')

@section('content')

<h2>Pinjaman Saat Ini</h2>

<table border="1" cellpadding="10" style="margin-top:20px;width:100%">

    <tr>
        <th>Kode Eksemplar</th>
        <th>Judul Buku</th>
        <th>Tanggal Pinjam</th>
        <th>Tanggal Kembali</th>
    </tr>

    @forelse($loans as $loan)

    <tr>
        <td>{{ $loan->kode_eksemplar }}</td>
        <td>{{ $loan->book->judul }}</td>
        <td>{{ $loan->tanggal_pinjam }}</td>
        <td>{{ $loan->tanggal_kembali }}</td>
    </tr>

    @empty

    <tr>
        <td colspan="4">Tidak ada buku yang sedang dipinjam</td>
    </tr>

    @endforelse

</table>

@endsection
