@extends('layouts.staff')

@section('content')

<h2>Tambah Bibliografi Baru (Tambah Buku)</h2>

<form method="POST" action="/books" enctype="multipart/form-data">

@csrf

<p>Judul Buku</p>
<input type="text" name="judul">

<p>Pengarang</p>
<input type="text" name="pengarang">

<p>Edisi Buku</p>
<input type="text" name="edisi">

<p>ISBN / ISSN</p>
<input type="text" name="isbn_issn">

<p>Tahun Terbit</p>
<input type="number" name="tahun_terbit">

<p>Tempat Terbit</p>
<input type="text" name="tempat_terbit">

<p>Deskripsi Fisik</p>
<input type="text" name="deskripsi_fisik">

<p>Bahasa Buku</p>
<input type="text" name="bahasa">

<p>Gambar Buku</p>
<input type="file" name="gambar">

<br><br>

<button type="submit">Simpan Buku</button>

</form>

@endsection
