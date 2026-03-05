@extends('layouts.staff')

@section('content')

<style>

.page-title{
font-size:22px;
font-weight:600;
margin-bottom:20px;
}

.card{
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 4px 10px rgba(0,0,0,0.05);
max-width:500px;
}

.form-group{
margin-bottom:18px;
}

label{
display:block;
font-size:14px;
font-weight:600;
margin-bottom:6px;
}

input{
width:100%;
padding:10px;
border:1px solid #ddd;
border-radius:6px;
}

.btn{
padding:10px 16px;
border:none;
border-radius:6px;
cursor:pointer;
font-size:14px;
}

.btn-primary{
background:#2563eb;
color:white;
}

</style>

<div class="page-title">
Isi Eksemplar Buku
</div>

<div class="card">

<h3>{{ $book->judul }}</h3>

<p>Pengarang : {{ $book->pengarang }}</p>
<p>ISBN : {{ $book->isbn_issn }}</p>

<hr>

<form method="POST" action="/books/{{ $book->id }}/eksemplar">

@csrf

<div class="form-group">
<label>Nomor Eksemplar</label>
<input type="number" name="jumlah" placeholder="Masukkan jumlah eksemplar">
</div>

<button class="btn btn-primary" type="submit">
Simpan Eksemplar
</button>

</form>

</div>

@endsection
